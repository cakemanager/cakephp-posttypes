How To Contribute
=================

Patches and pull requests are a great way to contribute code back to CakePHP. 
Pull requests can be created in GitHub, and are preferred over patch files in ticket comments.

[doc_toc]

Setup
-----

To start contributing you need a working copy of CakePHP. 

Asuming you have composer installed you need to run this command to create a new CakePHP project:

	composer create-project --prefer-dist -s dev cakephp/app [app_name]
	
After creating a new project you have to create a fork of https://github.com/cakemanager/cakephp-posttypes

After the fork is made, clone your fork to your project:

	git clone git@github.com:YOURNAME/cakephp-posttypes.git
	
Now you have to load your plugin in your `config/bootstrap.php` file. You are ready to use the plugin, and put some changes on it.

> Note: Note that you need the CakeManager as well to work on this plugin. The CakeManager can be loaded via compser.

Working on a branch
-------------------

When you are planning a new feature, please make a new branch. This branch should be a child of the branche with the chosen version-number.

So, imagine I want to create the `Awesome Feature` to version 1.0. I will create the branch: `1.0-awesome-feature`.


Submitting a Pull Request
-----------------------

Are you sure everything is okay? When I started to learn contributing with Pull Request I learned the following:

- Use [Code Sniffer](https://github.com/cakephp/cakephp-codesniffer) to check if the code is valid to the code standard.
- Every created code must have valid tests.
- Every modified code must have valid tests. Just check them with `phpunit`.
- All code (not tests) should contain valid documentation (see: https://github.com/cakemanager/cakephp-posttypes/tree/1.0/docs).
- Tests should only contain comments about what the tests do.

After you passed all of these notes you are ready to create a Pull Request. This can be done via GitHub itself.
Click on the green icon on the left of your forked repository. 

Select the branch you worked on (`1.0-awesome-feature`) and the branch to request to (`1.0`).
