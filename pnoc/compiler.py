"""Compile multiple document files from a directory into a single output file."""

from __future__ import annotations

import os
from pathlib import Path

from .sorter import sort_files

# File extensions treated as plain-text documents
DEFAULT_EXTENSIONS = {".txt", ".md", ".rst", ".html", ".htm", ".csv", ".log"}


def compile_documents(
    input_dir: str,
    output_file: str,
    extensions: set[str] | None = None,
    sort_by: str = "name",
    reverse: bool = False,
    recursive: bool = False,
    separator: str = "\n\n{separator}\n\n",
) -> list[str]:
    """Compile documents from *input_dir* into *output_file*.

    Each matching file is appended to the output in the order determined by
    *sort_by* / *reverse*.  A separator comment is inserted between files so
    it is easy to see where each document begins and ends.

    Parameters
    ----------
    input_dir:
        Directory that contains the source documents.
    output_file:
        Path of the combined output file that will be created (or overwritten).
    extensions:
        Iterable of lower-cased extensions including the dot (e.g. ``{".md",
        ".txt"}``).  Defaults to :data:`DEFAULT_EXTENSIONS`.
    sort_by:
        Sorting key – one of ``"name"``, ``"date"``, ``"size"``, ``"type"``.
    reverse:
        When *True*, sort in descending order.
    recursive:
        When *True*, include files from sub-directories.
    separator:
        Template string placed between files.  ``{separator}`` is replaced
        with a line of dashes followed by the file name.

    Returns
    -------
    list[str]
        The paths of every file that was included in the output, in order.
    """
    if extensions is None:
        extensions = DEFAULT_EXTENSIONS

    files = [
        f
        for f in sort_files(input_dir, sort_by=sort_by, reverse=reverse, recursive=recursive)
        if Path(f["path"]).suffix.lower() in extensions
    ]

    if not files:
        raise ValueError(f"No matching documents found in {input_dir!r}")

    output_path = Path(output_file)
    output_path.parent.mkdir(parents=True, exist_ok=True)

    included: list[str] = []
    with output_path.open("w", encoding="utf-8") as out:
        for idx, file_info in enumerate(files):
            path = Path(file_info["path"])
            sep_line = "-" * 60
            sep_block = f"{sep_line}\nFILE: {file_info['name']}\n{sep_line}"
            if idx > 0:
                out.write(separator.format(separator=sep_block))
            else:
                out.write(f"{sep_block}\n\n")

            try:
                content = path.read_text(encoding="utf-8", errors="replace")
            except OSError as exc:
                content = f"[ERROR reading {path}: {exc}]\n"

            out.write(content)
            if not content.endswith("\n"):
                out.write("\n")
            included.append(str(path))

    return included
