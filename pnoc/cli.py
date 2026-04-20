"""Command-line interface for pnoc."""

from __future__ import annotations

import argparse
import sys

from .compiler import compile_documents, DEFAULT_EXTENSIONS
from .monitor import monitor_directory
from .sorter import SORT_KEYS, format_file_list, sort_files


def _build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        prog="pnoc",
        description="Document compiler – compile, list, and monitor files.",
    )
    parser.add_argument("--version", action="version", version="pnoc 0.1.0")
    sub = parser.add_subparsers(dest="command", metavar="COMMAND")
    sub.required = True

    # ── compile ──────────────────────────────────────────────────────────────
    p_compile = sub.add_parser(
        "compile",
        help="Compile documents from a directory into a single output file.",
    )
    p_compile.add_argument("directory", help="Source directory containing documents.")
    p_compile.add_argument("output", help="Path of the compiled output file.")
    p_compile.add_argument(
        "--sort-by",
        choices=SORT_KEYS,
        default="name",
        help="Sort order for source files (default: name).",
    )
    p_compile.add_argument(
        "--reverse",
        action="store_true",
        help="Sort in descending order.",
    )
    p_compile.add_argument(
        "--recursive",
        action="store_true",
        help="Include files from sub-directories.",
    )
    p_compile.add_argument(
        "--ext",
        dest="extensions",
        metavar="EXT",
        nargs="+",
        help=(
            "File extensions to include (e.g. .md .txt). "
            f"Defaults to {sorted(DEFAULT_EXTENSIONS)}."
        ),
    )

    # ── list ─────────────────────────────────────────────────────────────────
    p_list = sub.add_parser(
        "list",
        help="List files in a directory with sortable metadata.",
    )
    p_list.add_argument("directory", nargs="?", default=".", help="Directory to list (default: .).")
    p_list.add_argument(
        "--sort-by",
        choices=SORT_KEYS,
        default="name",
        help="Sort key (default: name).",
    )
    p_list.add_argument("--reverse", action="store_true", help="Sort in descending order.")
    p_list.add_argument("--recursive", action="store_true", help="Include sub-directories.")

    # ── monitor ──────────────────────────────────────────────────────────────
    p_monitor = sub.add_parser(
        "monitor",
        help="Monitor a directory for file changes.",
    )
    p_monitor.add_argument(
        "directory", nargs="?", default=".", help="Directory to watch (default: .)."
    )
    p_monitor.add_argument(
        "--interval",
        type=float,
        default=1.0,
        metavar="SECONDS",
        help="Poll interval in seconds (default: 1.0).",
    )
    p_monitor.add_argument(
        "--recursive",
        action="store_true",
        help="Watch sub-directories as well.",
    )

    return parser


def main(argv: list[str] | None = None) -> int:
    parser = _build_parser()
    args = parser.parse_args(argv)

    if args.command == "compile":
        extensions = (
            {e if e.startswith(".") else f".{e}" for e in args.extensions}
            if args.extensions
            else None
        )
        try:
            included = compile_documents(
                args.directory,
                args.output,
                extensions=extensions,
                sort_by=args.sort_by,
                reverse=args.reverse,
                recursive=args.recursive,
            )
        except (NotADirectoryError, ValueError) as exc:
            print(f"Error: {exc}", file=sys.stderr)
            return 1
        print(f"Compiled {len(included)} file(s) → {args.output}")
        for path in included:
            print(f"  {path}")
        return 0

    elif args.command == "list":
        try:
            files = sort_files(
                args.directory,
                sort_by=args.sort_by,
                reverse=args.reverse,
                recursive=args.recursive,
            )
        except NotADirectoryError as exc:
            print(f"Error: {exc}", file=sys.stderr)
            return 1
        print(format_file_list(files))
        return 0

    elif args.command == "monitor":
        try:
            monitor_directory(
                args.directory,
                interval=args.interval,
                recursive=args.recursive,
            )
        except NotADirectoryError as exc:
            print(f"Error: {exc}", file=sys.stderr)
            return 1
        return 0

    return 0


if __name__ == "__main__":
    sys.exit(main())
