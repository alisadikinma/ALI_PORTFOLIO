# .claude Directory

This directory contains the Claude Code workflow configuration for the ALI_PORTFOLIO project.

## Structure

```
.claude/
├── agents/          # Specialized review agents
├── commands/        # Executable workflow commands
└── settings.local.json
```

## Commands Available

### Core Review Commands
- `code-review.md` - Complete code quality and architecture review
- `design-review.md` - UI/UX design review with accessibility checks
- `security-review.md` - Security vulnerability assessment
- `mvc-architecture-review.md` - Laravel MVC pattern compliance review

### Specialized Commands
- `genz-homepage-review.md` - Gen Z design appeal assessment and improvement
- `comprehensive-quality-review.md` - Responsive design, navigation, and UX testing using MCP Playwright

## Agents Available

- `code-reviewer.md` - Expert code review specialist
- `design-reviewer.md` - Elite design review specialist

## Usage

Run any command from Claude Code using:
```bash
@command-name
```

Example:
```bash
@genz-homepage-review
@comprehensive-quality-review
```

## Configuration

`settings.local.json` contains local project-specific settings for the Claude Code workflow.

## Integration

This workflow is integrated with:
- MCP Playwright tools for browser testing
- Laravel project structure and conventions
- Design principles located in `/context/` directory
