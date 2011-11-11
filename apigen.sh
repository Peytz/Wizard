#!/usr/bin/env bash

if ! git diff-index --quiet HEAD --; then
    echo "Seems like you have uncommitted changes"
    exit
fi

ORIGINAL_BRANCH_NAME=$(git branch --no-color 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/\1/')

# Stash all saved work if that exists
git checkout master

# Generate and commit apigen
apigen --source src --destination doc
git commit -m 'Update ApiGen' doc

# Save last commit so we can cherry-pick it later
LAST_COMMIT_HASH=$(git rev-parse HEAD)

# Switch branch and cherry pick
git checkout gh-pages
git cherry-pick $LAST_COMMIT_HASH

# Go back to previous branch and pop stash
git checkout $ORIGINAL_BRANCH_NAME

echo "Update ApiGen in doc/ based on master branch. Also cherry-picked it to gh-pages."
echo "You should properly push gh-pages"
