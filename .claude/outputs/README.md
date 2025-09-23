# Claude Agent Outputs

This directory contains all outputs from Claude agents to keep the project root clean.

## Directory Structure

```
.claude/outputs/
├── reports/          # Comprehensive reports from meta-orchestration
├── analysis/         # Individual agent analysis files
├── artifacts/        # Generated files, screenshots, data exports
└── README.md         # This file
```

## File Naming Convention

All files use timestamp format: `YYYY-MM-DD_HH-mm-ss`

### Report Types
- `comprehensive_audit_YYYY-MM-DD_HH-mm-ss.md` - Full system audits
- `security_report_YYYY-MM-DD_HH-mm-ss.md` - Security analysis
- `code_review_YYYY-MM-DD_HH-mm-ss.md` - Code quality reviews
- `design_analysis_YYYY-MM-DD_HH-mm-ss.md` - UI/UX analysis
- `performance_report_YYYY-MM-DD_HH-mm-ss.md` - Performance audits

### Analysis Types
- `agent_name_analysis_YYYY-MM-DD_HH-mm-ss.md` - Individual agent outputs
- `workflow_state_YYYY-MM-DD_HH-mm-ss.json` - Workflow tracking
- `metrics_YYYY-MM-DD_HH-mm-ss.json` - Performance metrics

### Artifacts
- `screenshots/` - Test screenshots and UI captures
- `exports/` - Data exports and backups
- `generated/` - Auto-generated code or configs

## Usage Instructions

All Claude agents should output files to appropriate subdirectories instead of project root.

## Cleanup Policy

- Files older than 30 days are automatically archived
- Root folder files should be moved here manually
- Use `cleanup_old_reports.php` script for maintenance
