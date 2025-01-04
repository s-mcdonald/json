# Contributing Guidelines

Thank you for taking the time to contribute to this PHPJson! Here is a set of guidelines to help you contribute effectively while maintaining the quality and consistency of the project.

---

## Table of Contents:
1. [Code of Conduct](#code-of-conduct)
2. [Ways to Contribute](#ways-to-contribute)
3. [Getting Started](#getting-started)
4. [Submitting Changes](#submitting-changes)
5. [Code Standards](#code-standards)
6. [Testing](#testing)
7. [Bug Reports and Feature Requests](#bug-reports-and-feature-requests)
8. [Documentation](#documentation)
9. [License](#license)

---

## 1. Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you agree to uphold this code. Please be respectful and collaborative in all interactions.

---

## 2. Ways to Contribute

You can contribute to this project in several ways:
- Reporting bugs or suggesting features.
- Submitting pull requests to fix bugs or implement new features.
- Improving documentation.
- Writing tests for the codebase.
- Reviewing existing pull requests.

---

## 3. Getting Started

To get started with contributing to this project, follow these steps:

1. **Fork the Repository**: Click on the "Fork" button at the top of the repository page to create a copy in your own GitHub account.
2. **Clone Your Fork**:
    ```bash
    git clone https://github.com/s-mcdonald/PHPJson.git
    cd PHPJson
    ```
3. **Install Dependencies** (if applicable):
    ```bash
    composer install
    ```
4. **Create a Branch for Your Contribution**:
    ```bash
    git checkout -b feature/your-feature-name
    ```

---

## 4. Submitting Changes

1. **Commit Your Changes**:
    - Write clear, descriptive commit messages.
    - Follow this format:
        ```
        [Fix/Feature/Docs] A brief description of the change
        ```
      Example:
        ```
        [Fix] Resolve issue with array serialization
        [Feature] Add support for custom normalizers
        ```
    - Make sure your changes pass all tests before committing.

2. **Push Your Branch** to Your Forked Repository:
    ```bash
    git push origin feature/your-feature-name
    ```

3. **Create a Pull Request**:
    - Go to the original repository and click "New Pull Request".
    - Ensure that your pull request clearly explains:
        - What the change is.
        - Why it's needed.
        - Any potential impacts.

4. Wait for a reviewer to evaluate your PR. Be open to feedback and make necessary adjustments if requested.

---

## 5. Code Standards

Follow these coding standards to ensure the codebase remains consistent and maintainable:

1. **PHP Version**: Use PHP 8.2 or later unless otherwise stated in the project.
2. **Coding Style**: Adhere to [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.
    - Use a linter such as `phpcs` for automatic standard checking:
      ```bash
      composer phpcs
      ```
    - Run auto-formatting when needed:
      ```bash
      composer phpcbf
      ```
3. **Naming Conventions**:
    - Use meaningful variable, method, and class names.
    - Consistent camelCase for variable names and PascalCase for class names.
4. **Type Hints**: Use strict types (`declare(strict_types=1);`) and type hints for all functions and methods.
5. **Comments and Documentation**:
    - Provide clear, concise comments where needed.
    - Use PHPDoc for documenting classes, methods, and significant code sections.

---

## 6. Testing

All code contributions **must include appropriate automated tests** to ensure functionality and prevent regressions.

1. **Run Tests**: Make sure your changes pass the existing test suite:
    ```bash
    composer test
    ```
2. **Add New Tests** for:
    - Any new functionality introduced.
    - Edge cases related to the change.


---

## 7. Bug Reports and Feature Requests

We encourage you to use the [GitHub Issues](https://github.com/s-mcdonald/PHPJson/issues) page to:
- Report bugs (please include steps to reproduce, expected behavior, and logs if applicable).
- Submit feature requests (outline the problem, possible solutions, and use cases).

When creating an issue:
- Use clear and descriptive titles.
- Mark it with the appropriate labels (e.g., `bug`, `enhancement`, or `documentation`).

---

## 8. Documentation

- If your changes affect functionality, ensure the relevant documentation is updated.
- Add examples in usage guides when introducing new features.
- Documentation follows Markdown formatting (`.md` files).

---

## 9. License

By contributing to this project, you agree that your contributions will be licensed under the same license as the project—refer to the `LICENSE` file for details.

---

Thank you for contributing! 🎉  
Feel free to reach out in the discussions or issues if you have any questions.
