# Introduction

This repository is used as a template for all new Zend Framework project, it
has a branch for each ORM, the ActiveRecord branch being the default branch,
and by default I mean you merge from it and not to it..

# Usage

To use this repository clone one of the branches to a new path and
develop over it, you can keep the template as a remote to get regular
fixes to the template.

# Developement

To develop this template always use the ActiveRecord branch, the ActiveRecord
branch can be safely merged into other branches, if you merge into it, you'll
need to revert commit that changes the ORM become compatible with MongoID
instead of ActiveRecord.

# Issues

To report issues please use [Redmine](http://redmine.technogate.fr/projects/zf-template)

# How to contribute

If you find what looks like a bug:

1. Check the [Redmine issue tracker](http://redmine.technogate.fr/projects/zf-template/issues) to see if anyone else has reported issue.
2. If you don’t see anything, create an issue with information on how to reproduce it.

If you want to contribute an enhancement or a fix:

1. Fork the project on github.
2. Make your changes with tests.
3. Commit the changes without making changes to any other files that aren’t related to your enhancement or fix
4. Send a pull request.
