#!/bin/bash
# Cloudlog update script
#
# Pulls changes from Git remote and re-sets appropriate directory ownership.
# Can be run manually or on a schedule using Cron.
#
# Please check the DIR_OWNERSHIP variable carefully to make sure it is
# appropriately set for your system!

# The user and group that own the CLOUDLOG_SUBDIR directories. Passed to 'chown' as-is.
DIR_OWNERSHIP="root:www-data"
# The list of directories that need to have ownership restored after a git pull
declare -a CLOUDLOG_SUBDIRS=("application/config" "assets" "backup" "updates" "uploads" "images/eqsl_card_images")
# The name of the Git remote to fetch/pull from
GIT_REMOTE="origin"
# If true, pull from the HEAD of the configured origin, otherwise the latest tag
BLEEDING_EDGE="true"
# If true, restore directory ownership on CLOUDLOG_SUBDIRS after a git pull
RESTORE_OWNERSHIP="true"

check_working_dir() {
	# Quick sanity check to make sure that pwd looks like a Cloudlog install
	if [[ ! -d "$(pwd)/application" ]]
	then
		echo "$(pwd) doesn't look like a Cloudlog install directory! Stopping here."
		exit 1
	fi
}

fast_forward_to_tag() {
	# Find the latest tag on the current branch
	# See https://git-scm.com/docs/git-describe for details on retrieving tags
	LATEST_TAG=$(git describe --tags --abbrev=0)
	echo "Fast-forwarding to latest tag: $LATEST_TAG..."
	if git pull $GIT_REMOTE $LATEST_TAG ; then
		echo "Fast-forward finished successfully."
	else
		echo "Fast-forward failed; stopping here."
		exit 2
	fi
}

fast_forward_to_head() {
	echo "Fast-forwarding to HEAD (bleeding-edge)..."
	if git pull $GIT_REMOTE ; then
		echo "Fast-forward finished successfully".
	else
		echo "Fast-forward failed; stopping here."
		exit 3
	fi
}

restore_ownership() {
	for dir in "${CLOUDLOG_SUBDIRS[@]}"; do
		echo "Setting ownership as $DIR_OWNERSHIP on $dir"
		chown -R $DIR_OWNERSHIP $(pwd)/$dir
	done
}


echo "Cloudlog update started"
echo "-----------------------"
echo "Using $(pwd) as working directory"

check_working_dir

# Fetch the latest changes from the master branch
echo "Fetching changes from remote..."
if git fetch $GIT_REMOTE --tags ; then
	echo "Fetched latest changes successfully"
else
	echo "Fetch failed; stopping here."
	exit 4
fi

if [ "$BLEEDING_EDGE" = true ]; then
	fast_forward_to_head
else
	fast_forward_to_tag
fi

if [ "$RESTORE_OWNERSHIP" = true ]; then
	restore_ownership
fi

echo "Cloudlog update finished"
