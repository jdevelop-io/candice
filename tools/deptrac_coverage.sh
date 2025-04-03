#!/usr/bin/env bash

# This script ensures that all bounded contexts are properly covered by deptrac.
# Each bounded context should have a corresponding deptrac.baseline.yaml file and each one should be imported in the
# main deptrac.yaml file.

# Define the directory containing bounded contexts directories
BOUNDED_CONTEXTS_ROOT_DIR="src"

# Define the path to the main deptrac.yaml file
DEPTRAC_FILE="deptrac.yaml"

# Define the name of the baseline file
BASELINE_FILE="deptrac.baseline.yaml"

# Check if the main deptrac.yaml file exists
if [ ! -f "$DEPTRAC_FILE" ]; then
    echo "Error: $DEPTRAC_FILE file not found!"
    exit 1
fi

# Check if the bounded contexts directory exists
if [ ! -d "$BOUNDED_CONTEXTS_ROOT_DIR" ]; then
    echo "Error: $BOUNDED_CONTEXTS_ROOT_DIR directory not found!"
    exit 1
fi

# Loop through each bounded context and check for the presence of $BASELINE_FILE
MISSING_FILES=()
MISCONFIGURED_FILES=()
MISSING_IMPORTS=()

# Search for the imports section in the main $DEPTRAC_FILE file
IMPORTS_SECTION=$(awk '/^imports:/ {flag=1; next} /^[^ ]/ {flag=0} flag' "$DEPTRAC_FILE" | grep -E '^\s+-\s+')

for BOUNDED_CONTEXT_DIR in "$BOUNDED_CONTEXTS_ROOT_DIR"/*; do
    # Check if it's a directory
    if [ -d "$BOUNDED_CONTEXT_DIR" ]; then
        BOUNDED_CONTEXT_NAME=$(basename "$BOUNDED_CONTEXT_DIR")

        # Check if the $BASELINE_FILE file exists in the bounded context directory
        if [ ! -f "$BOUNDED_CONTEXT_DIR/$BASELINE_FILE" ]; then
            MISSING_FILES+=("$BOUNDED_CONTEXT_DIR")
        fi

        # Check in deptrac paths section if the bounded context is present
        # It should be under the "paths" section, each path should be on a new line starting with a hyphen
        # Check if the $BOUNDED_CONTEXT_DIR is present in the paths section
        PATHS_SECTION=$(awk '
                        /^deptrac:/ { in_deptrac=1 }
                        in_deptrac && /^[[:space:]]+paths:/ { flag=1; next }
                        flag && /^[^[:space:]]/ { flag=0 }
                        flag { gsub(/^[[:space:]]*-\s*/, ""); print }
                        ' $BOUNDED_CONTEXT_DIR/$BASELINE_FILE | xargs)

        if ! echo "$PATHS_SECTION" | grep -q "$BOUNDED_CONTEXT_DIR"; then
            MISCONFIGURED_FILES+=("$BOUNDED_CONTEXT_NAME ($BOUNDED_CONTEXT_DIR/$BASELINE_FILE)")
        fi

        # Check if the $BASELINE_FILE is imported in the main $DEPTRAC_FILE
        # It should be under the "imports" section, each imports should be on a new line starting with a hyphen

        # Check if the $BASELINE_FILE is present in the imports section
        if ! echo "$IMPORTS_SECTION" | grep -q "$BOUNDED_CONTEXT_DIR/$BASELINE_FILE"; then
            MISSING_IMPORTS+=("$BOUNDED_CONTEXT_NAME ($BOUNDED_CONTEXT_DIR/$BASELINE_FILE)")
        fi
    fi
done

# Report misconfigured files
if [ ${#MISCONFIGURED_FILES[@]} -ne 0 ]; then
    echo "The following bounded contexts are misconfigured in the $BASELINE_FILE file:"
    for FILE in "${MISCONFIGURED_FILES[@]}"; do
        echo "- $FILE"
    done
else
    echo "All bounded contexts are properly configured in the $BASELINE_FILE file."
fi

# Report missing files
if [ ${#MISSING_FILES[@]} -ne 0 ]; then
    echo "The following bounded contexts are missing the $BASELINE_FILE file:"
    for FILE in "${MISSING_FILES[@]}"; do
        echo "- $FILE"
    done
else
    echo "All bounded contexts have the $BASELINE_FILE file."
fi

# Report missing imports
if [ ${#MISSING_IMPORTS[@]} -ne 0 ]; then
    echo "The following bounded contexts are missing the imports in $DEPTRAC_FILE:"
    for IMPORT in "${MISSING_IMPORTS[@]}"; do
        echo "- $IMPORT"
    done
else
    echo "All bounded contexts are properly imported in $DEPTRAC_FILE."
fi
