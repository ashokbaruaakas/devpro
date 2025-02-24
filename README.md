<p align="center">
    <img src="/arts/devpulse-cli-logo.svg" alt="Overview DevPulse PHP" style="width:200px">
</p>

---

**DevPulse** is an open-source command-line tool that simplifies and unifies developer workflows. Install it globally, define project-specific or reusable scripts (e.g., builds, tests, deployments), and run them with `devpulse run <script>`. Designed for flexibility, **DevPulse** lets you replace scattered scripts and tools with a single interface, empowering teams to standardize workflows without locking into specific languages or ecosystems.

> Note: DevPulse is still under active development and is not yet ready for production use.

## Installation

Coming Soon...

## Usage

Coming Soon...

## Configuration

DevPulse can be configured using a `devpulse.json` file in the root of your project.

You can scaffold the `devpulse.json` file with:

```bash
devpulse init
```

Here's an example configuration:

```json
{
    "scripts": [
        {
            "name": "php",
            "description": "Print current PHP version.",
            "command": "php --version"
        }
    ]
}
```

### Presets

Coming Soon...

## Command Options

Coming Soon...

---

DevPulse is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
