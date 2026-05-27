# Contributing to MyTopup

First off, thank you for considering contributing to MyTopup! It's people like you that make MyTopup such a great tool.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

* **Use a clear and descriptive title**
* **Describe the exact steps which reproduce the problem**
* **Provide specific examples to demonstrate the steps**
* **Describe the behavior you observed after following the steps**
* **Explain which behavior you expected to see instead and why**
* **Include screenshots and animated GIFs** if possible

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

* **Use a clear and descriptive title**
* **Provide a step-by-step description of the suggested enhancement**
* **Provide specific examples to demonstrate the steps**
* **Describe the current behavior** and **explain which behavior you expected to see instead**
* **Explain why this enhancement would be useful**

### Pull Requests

* Fill in the required template
* Do not include issue numbers in the PR title
* Follow the PHP and Laravel coding standards
* Include thoughtfully-worded, well-structured tests
* Document new code based on the Documentation Styleguide
* End all files with a newline

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/YOUR_USERNAME/store_laravel.git`
3. Create a branch: `git checkout -b feature/my-new-feature`
4. Install dependencies: `composer install`
5. Copy `.env.example` to `.env` and configure
6. Generate app key: `php artisan key:generate`
7. Run migrations: `php artisan migrate`
8. Make your changes
9. Run tests: `php artisan test`
10. Commit your changes: `git commit -am 'Add some feature'`
11. Push to the branch: `git push origin feature/my-new-feature`
12. Submit a pull request

## Coding Standards

* Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
* Use Laravel best practices
* Write meaningful commit messages
* Add comments for complex logic
* Keep functions small and focused
* Use type hints where possible

## Testing

* Write tests for new features
* Ensure all tests pass before submitting PR
* Aim for high code coverage

## Documentation

* Update README.md if needed
* Add PHPDoc comments to new methods
* Update CHANGELOG.md

## Questions?

Feel free to open an issue with your question or reach out to the maintainers.

Thank you for contributing! 🎉
