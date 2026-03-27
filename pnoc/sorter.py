"""Sort and list files in a directory by various criteria."""

import os
from datetime import datetime, timezone
from pathlib import Path


SORT_KEYS = ("name", "date", "size", "type")


def get_file_info(path: Path) -> dict:
    """Return a dict of metadata for *path*."""
    stat = path.stat()
    return {
        "name": path.name,
        "path": str(path),
        "size": stat.st_size,
        "modified": datetime.fromtimestamp(stat.st_mtime, tz=timezone.utc),
        "type": path.suffix.lower().lstrip(".") or "—",
    }


def sort_files(
    directory: str,
    sort_by: str = "name",
    reverse: bool = False,
    recursive: bool = False,
) -> list[dict]:
    """Return a list of file metadata dicts sorted by *sort_by*.

    Parameters
    ----------
    directory:
        Path to the directory to list.
    sort_by:
        One of ``"name"``, ``"date"``, ``"size"``, or ``"type"``.
    reverse:
        When *True*, sort in descending order.
    recursive:
        When *True*, include files in sub-directories.
    """
    if sort_by not in SORT_KEYS:
        raise ValueError(f"sort_by must be one of {SORT_KEYS}; got {sort_by!r}")

    root = Path(directory)
    if not root.is_dir():
        raise NotADirectoryError(f"{directory!r} is not a directory")

    pattern = "**/*" if recursive else "*"
    files = [get_file_info(p) for p in root.glob(pattern) if p.is_file()]

    key_map = {
        "name": lambda f: f["name"].lower(),
        "date": lambda f: f["modified"],
        "size": lambda f: f["size"],
        "type": lambda f: (f["type"], f["name"].lower()),
    }
    files.sort(key=key_map[sort_by], reverse=reverse)
    return files


def format_file_list(files: list[dict]) -> str:
    """Return a human-readable table of *files*."""
    if not files:
        return "(no files)"

    header = f"{'Name':<40} {'Type':<8} {'Size':>10}  {'Modified'}"
    separator = "-" * len(header)
    rows = [header, separator]
    for f in files:
        size_str = _human_size(f["size"])
        mod_str = f["modified"].strftime("%Y-%m-%d %H:%M")
        rows.append(f"{f['name']:<40} {f['type']:<8} {size_str:>10}  {mod_str}")
    return "\n".join(rows)


def _human_size(size: int) -> str:
    for unit in ("B", "KB", "MB", "GB"):
        if size < 1024:
            return f"{size:.0f} {unit}"
        size /= 1024
    return f"{size:.1f} TB"
