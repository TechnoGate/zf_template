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
