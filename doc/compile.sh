#!/bin/bash
#
# vim:syntax=sh:sw=4:ts=4:expandtab
#
# Author: Wael Nasreddine (TechnoGate) <wael@technogate.fr>


# Sanity Check
if [ ! -x "`/usr/bin/which markdown 2>/dev/null`" ]; then
    echo "Error: Markdown is not installed."
    echo "http://daringfireball.net/projects/markdown/"
    exit 1
fi

TECHNOGATE_ROOT="$(cd $(dirname $0)/.. && pwd)"

cd "${TECHNOGATE_ROOT}"

pushd "${TECHNOGATE_ROOT}/doc/_src" > /dev/null

for file in *.markdown; do
    newFileName="../${file/\.markdown/.html}"
    cat header.html > "${newFileName}"
    markdown --html4tags "${file}" >> "${newFileName}"
    cat footer.html >> "${newFileName}"
done

popd > /dev/null
