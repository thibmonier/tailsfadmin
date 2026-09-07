# MyProject - PHP Project

**Stack**: PHP 8.5, Composer, PSR Standards, PHPUnit 12

> Note (rétro Sprint 1) : **PHPUnit 12** est retenu comme framework de test. Pest 4 exige PHPUnit 11 et est donc **incompatible** avec PHPUnit 12 — ne pas l'ajouter à cette stack.

## Quick Reference

See `@.claude/INDEX.md` for condensed checklists and patterns.

## Full Documentation

For detailed rules and examples: `@.claude/references/<topic>.md`

## Available Commands

- `/php:check-compliance` - Full compliance audit
- `/php:check-architecture` - Architecture validation
- `/php:check-code-quality` - Code quality analysis
- `/php:check-testing` - Test coverage analysis
- `/php:check-security` - Security audit (OWASP)

## Docker Requirement

Always use Docker for commands to abstract from local environment.
