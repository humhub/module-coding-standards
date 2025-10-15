# HumHub - Module Coding Standards

Central repository for **code quality**, **Rector rules**, and **developer tooling**  
used across all **HumHub modules**.

> This repository is **only intended for HumHub module development**, not for the core framework itself.

## Installation

### Composer 

#### To your existing `composer.json`
Add the package to your module’s `composer.json` as a development dependency:

```bash
composer config repositories.humhub-module-coding-standards vcs https://github.com/humhub/module-coding-standards.git
composer require --dev humhub/module-coding-standards:dev-main
```

Add script section to your `composer.json`:

```json
{
  "scripts": {
    "rector": "vendor/bin/rector process --config=vendor/humhub/module-coding-standards/rector.php"
  }
}
```

#### Or create a minimal `composer.json`

```yaml
{
  "name": "humhub/polls",
  "type": "humhub-module",
  "config": {
    "platform": {
      "php": "8.2"
    }
  },
  "repositories": {
    "humhub-module-coding-standards": {
      "type": "vcs",
      "url": "https://github.com/humhub/module-coding-standards.git"
    }
  },
  "require-dev": {
    "humhub/module-coding-standards": "dev-main"
  },
  "scripts": {
    "rector": "vendor/bin/rector process --config=vendor/humhub/module-coding-standards/rector.php"
  }
}
```
> Hint: Please always commit your `composer.lock`.

### Install Workflows

```
mkdir -p .github/workflows
cp vendor/humhub/module-coding-standards/workflows/rector-auto-pr.yaml .github/workflows
```