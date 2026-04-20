# pnoc

A **document compiler** that makes it easy to monitor and sort files.

## Features

| Feature | Description |
|---------|-------------|
| **compile** | Merge multiple documents from a directory into a single output file |
| **list** | List files with metadata, sortable by name, date, size, or type |
| **monitor** | Watch a directory for file-system changes (create / modify / delete) |

## Installation

```bash
pip install .
```

## Usage

### Compile documents

```bash
# Compile all supported documents in ./docs into compiled.txt
pnoc compile ./docs compiled.txt

# Sort source files by modification date (newest last)
pnoc compile ./docs compiled.txt --sort-by date

# Include only Markdown files, sorted by name descending
pnoc compile ./docs compiled.txt --ext .md --sort-by name --reverse
```

### List files

```bash
# List files in the current directory sorted by name (default)
pnoc list

# Sort by size, largest first
pnoc list ./docs --sort-by size --reverse

# Sort by type, include sub-directories
pnoc list ./docs --sort-by type --recursive
```

### Monitor a directory

```bash
# Watch the current directory for changes
pnoc monitor

# Watch ./docs, polling every 2 seconds, including sub-directories
pnoc monitor ./docs --interval 2 --recursive
```

## Supported document extensions

`.txt`, `.md`, `.rst`, `.html`, `.htm`, `.csv`, `.log`

## Sort keys

| Key    | Description              |
|--------|--------------------------|
| `name` | Alphabetical (default)   |
| `date` | Last-modified timestamp  |
| `size` | File size in bytes       |
| `type` | File extension           |

## Running tests

```bash
pip install pytest
pytest tests/
```
