"""Monitor a directory for file-system changes using polling."""

from __future__ import annotations

import os
import time
from collections.abc import Callable
from pathlib import Path
from typing import NamedTuple


class FileEvent(NamedTuple):
    """Describes a single file-system event."""

    kind: str   # "created", "modified", or "deleted"
    path: str


def _snapshot(directory: str, recursive: bool) -> dict[str, float]:
    """Return a {path: mtime} mapping for all files under *directory*."""
    root = Path(directory)
    pattern = "**/*" if recursive else "*"
    return {
        str(p): p.stat().st_mtime
        for p in root.glob(pattern)
        if p.is_file()
    }


def monitor_directory(
    directory: str,
    callback: Callable[[FileEvent], None] | None = None,
    interval: float = 1.0,
    recursive: bool = False,
    stop_after: float | None = None,
) -> None:
    """Poll *directory* and invoke *callback* whenever a file changes.

    This function blocks until interrupted (``KeyboardInterrupt``) or until
    *stop_after* seconds have elapsed.

    Parameters
    ----------
    directory:
        Directory to watch.
    callback:
        Called with a :class:`FileEvent` for every detected change.  When
        *None*, events are printed to stdout.
    interval:
        Seconds between polls.
    recursive:
        When *True*, also watch sub-directories.
    stop_after:
        If given, stop monitoring after this many seconds.  Useful for
        automated tests.
    """
    if not Path(directory).is_dir():
        raise NotADirectoryError(f"{directory!r} is not a directory")

    if callback is None:
        def default_callback(event: FileEvent) -> None:
            ts = time.strftime("%Y-%m-%d %H:%M:%S")
            print(f"[{ts}] {event.kind.upper():8s} {event.path}")
        callback = default_callback

    print(f"Monitoring {directory!r} (press Ctrl-C to stop)…")
    before = _snapshot(directory, recursive)
    start = time.monotonic()

    try:
        while True:
            time.sleep(interval)

            if stop_after is not None and time.monotonic() - start >= stop_after:
                break

            after = _snapshot(directory, recursive)

            for path in sorted(set(after) - set(before)):
                callback(FileEvent("created", path))

            for path in sorted(set(before) - set(after)):
                callback(FileEvent("deleted", path))

            for path in sorted(set(before) & set(after)):
                if after[path] != before[path]:
                    callback(FileEvent("modified", path))

            before = after

    except KeyboardInterrupt:
        print("\nMonitoring stopped.")
