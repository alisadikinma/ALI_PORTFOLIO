#!/bin/bash

# Navigate to project directory
cd "C:\xampp\htdocs\ALI_PORTFOLIO"

# Show current git status
echo "=== Current Git Status ==="
git status

# Add all files
echo "=== Adding all files ==="
git add .

# Show status after adding
echo "=== Status after adding files ==="
git status

# Commit the merge resolution
echo "=== Committing merge resolution ==="
git commit -m "Resolve merge conflicts in portfolio_detail.blade.php and clean up routes"

echo "=== Final status ==="
git status

echo "Conflicts resolved successfully!"
