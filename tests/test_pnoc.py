"""Tests for pnoc – document compiler."""

from __future__ import annotations

import os
import time
from pathlib import Path

import pytest

from pnoc.compiler import compile_documents, DEFAULT_EXTENSIONS
from pnoc.monitor import FileEvent, monitor_directory, _snapshot
from pnoc.sorter import sort_files, format_file_list, SORT_KEYS


# ── helpers ──────────────────────────────────────────────────────────────────

def _make_files(tmp_path: Path, specs: list[tuple[str, str]]) -> None:
    """Create files under *tmp_path* from (name, content) pairs."""
    for name, content in specs:
        (tmp_path / name).write_text(content, encoding="utf-8")


# ── sorter tests ──────────────────────────────────────────────────────────────

def test_sort_files_by_name(tmp_path):
    _make_files(tmp_path, [("b.txt", "B"), ("a.txt", "A"), ("c.txt", "C")])
    result = sort_files(str(tmp_path), sort_by="name")
    assert [f["name"] for f in result] == ["a.txt", "b.txt", "c.txt"]


def test_sort_files_by_name_reverse(tmp_path):
    _make_files(tmp_path, [("b.txt", "B"), ("a.txt", "A"), ("c.txt", "C")])
    result = sort_files(str(tmp_path), sort_by="name", reverse=True)
    assert [f["name"] for f in result] == ["c.txt", "b.txt", "a.txt"]


def test_sort_files_by_size(tmp_path):
    _make_files(tmp_path, [("big.txt", "X" * 100), ("small.txt", "x"), ("medium.txt", "X" * 50)])
    result = sort_files(str(tmp_path), sort_by="size")
    assert [f["name"] for f in result] == ["small.txt", "medium.txt", "big.txt"]


def test_sort_files_by_type(tmp_path):
    _make_files(tmp_path, [("doc.txt", ""), ("doc.md", ""), ("doc.csv", "")])
    result = sort_files(str(tmp_path), sort_by="type")
    types = [f["type"] for f in result]
    assert types == sorted(types)


def test_sort_files_invalid_key(tmp_path):
    with pytest.raises(ValueError):
        sort_files(str(tmp_path), sort_by="invalid")


def test_sort_files_not_a_directory(tmp_path):
    with pytest.raises(NotADirectoryError):
        sort_files(str(tmp_path / "missing"))


def test_sort_files_recursive(tmp_path):
    sub = tmp_path / "sub"
    sub.mkdir()
    _make_files(tmp_path, [("root.txt", "r")])
    _make_files(sub, [("nested.txt", "n")])
    result = sort_files(str(tmp_path), recursive=True)
    names = {f["name"] for f in result}
    assert {"root.txt", "nested.txt"} == names


def test_format_file_list_empty():
    assert format_file_list([]) == "(no files)"


def test_format_file_list(tmp_path):
    _make_files(tmp_path, [("a.txt", "hello")])
    files = sort_files(str(tmp_path))
    output = format_file_list(files)
    assert "a.txt" in output
    assert "txt" in output


def test_file_info_keys(tmp_path):
    _make_files(tmp_path, [("x.md", "content")])
    info = sort_files(str(tmp_path))[0]
    for key in ("name", "path", "size", "modified", "type"):
        assert key in info


# ── compiler tests ────────────────────────────────────────────────────────────

def test_compile_creates_output(tmp_path):
    _make_files(tmp_path, [("a.txt", "alpha"), ("b.txt", "beta")])
    out = tmp_path / "compiled.txt"
    included = compile_documents(str(tmp_path), str(out))
    assert out.exists()
    assert len(included) == 2


def test_compile_content_order(tmp_path):
    _make_files(tmp_path, [("z.txt", "zzz"), ("a.txt", "aaa")])
    out = tmp_path / "out.txt"
    compile_documents(str(tmp_path), str(out), sort_by="name")
    content = out.read_text()
    assert content.index("aaa") < content.index("zzz")


def test_compile_filters_by_extension(tmp_path):
    _make_files(tmp_path, [("doc.txt", "text"), ("img.png", "image"), ("note.md", "markdown")])
    out = tmp_path / "out.txt"
    included = compile_documents(str(tmp_path), str(out), extensions={".txt"})
    assert all(p.endswith(".txt") for p in included)
    assert len(included) == 1


def test_compile_no_matching_files(tmp_path):
    _make_files(tmp_path, [("image.png", "data")])
    out = tmp_path / "out.txt"
    with pytest.raises(ValueError):
        compile_documents(str(tmp_path), str(out), extensions={".txt"})


def test_compile_sorted_by_size(tmp_path):
    _make_files(tmp_path, [("big.txt", "X" * 100), ("small.txt", "x")])
    out = tmp_path / "out.txt"
    included = compile_documents(str(tmp_path), str(out), sort_by="size")
    content = out.read_text()
    assert content.index("small.txt") < content.index("big.txt")


def test_compile_creates_parent_dirs(tmp_path):
    _make_files(tmp_path, [("a.txt", "content")])
    out = tmp_path / "nested" / "dir" / "out.txt"
    compile_documents(str(tmp_path), str(out))
    assert out.exists()


def test_compile_invalid_directory(tmp_path):
    with pytest.raises(NotADirectoryError):
        compile_documents(str(tmp_path / "missing"), str(tmp_path / "out.txt"))


# ── monitor tests ─────────────────────────────────────────────────────────────

def test_snapshot_empty(tmp_path):
    snap = _snapshot(str(tmp_path), recursive=False)
    assert snap == {}


def test_snapshot_detects_files(tmp_path):
    _make_files(tmp_path, [("a.txt", "hello")])
    snap = _snapshot(str(tmp_path), recursive=False)
    assert len(snap) == 1
    path_key = next(iter(snap))
    assert path_key.endswith("a.txt")


def test_monitor_detects_creation(tmp_path):
    events: list[FileEvent] = []

    def cb(event: FileEvent) -> None:
        events.append(event)

    # Start monitoring in a thread so we can write a file concurrently.
    import threading

    def run():
        monitor_directory(str(tmp_path), callback=cb, interval=0.1, stop_after=0.5)

    t = threading.Thread(target=run, daemon=True)
    t.start()
    time.sleep(0.15)
    (tmp_path / "new.txt").write_text("hello")
    t.join(timeout=2)

    kinds = {e.kind for e in events}
    assert "created" in kinds


def test_monitor_detects_deletion(tmp_path):
    f = tmp_path / "del.txt"
    f.write_text("bye")
    events: list[FileEvent] = []

    import threading

    def run():
        monitor_directory(str(tmp_path), callback=lambda e: events.append(e), interval=0.1, stop_after=0.5)

    t = threading.Thread(target=run, daemon=True)
    t.start()
    time.sleep(0.15)
    f.unlink()
    t.join(timeout=2)

    kinds = {e.kind for e in events}
    assert "deleted" in kinds


def test_monitor_invalid_directory(tmp_path):
    with pytest.raises(NotADirectoryError):
        monitor_directory(str(tmp_path / "missing"), stop_after=0)


# ── CLI tests ─────────────────────────────────────────────────────────────────

from pnoc.cli import main


def test_cli_list(tmp_path, capsys):
    _make_files(tmp_path, [("hello.txt", "world")])
    rc = main(["list", str(tmp_path)])
    assert rc == 0
    out = capsys.readouterr().out
    assert "hello.txt" in out


def test_cli_list_sort_by_size(tmp_path, capsys):
    _make_files(tmp_path, [("big.txt", "X" * 200), ("small.txt", "x")])
    rc = main(["list", str(tmp_path), "--sort-by", "size"])
    assert rc == 0


def test_cli_list_bad_directory(capsys):
    rc = main(["list", "/nonexistent_dir_pnoc"])
    assert rc == 1
    assert "Error" in capsys.readouterr().err


def test_cli_compile(tmp_path, capsys):
    _make_files(tmp_path, [("a.txt", "alpha"), ("b.md", "beta")])
    out = str(tmp_path / "compiled.txt")
    rc = main(["compile", str(tmp_path), out])
    assert rc == 0
    assert Path(out).exists()


def test_cli_compile_with_ext(tmp_path, capsys):
    _make_files(tmp_path, [("a.txt", "alpha"), ("b.png", "image")])
    out = str(tmp_path / "out.txt")
    rc = main(["compile", str(tmp_path), out, "--ext", ".txt"])
    assert rc == 0
    content = Path(out).read_text()
    assert "alpha" in content


def test_cli_compile_no_files(tmp_path, capsys):
    _make_files(tmp_path, [("img.png", "data")])
    rc = main(["compile", str(tmp_path), str(tmp_path / "out.txt"), "--ext", ".txt"])
    assert rc == 1


def test_cli_version(capsys):
    with pytest.raises(SystemExit) as exc:
        main(["--version"])
    assert exc.value.code == 0
