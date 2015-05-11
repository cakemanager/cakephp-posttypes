Documentation
=============

Contributing to the documentation is simple. The files are hosted on https://github.com/cakemanager/cakephp-posttypes/tree/1.0/docs. 
Feel free to fork the repo, add your changes/improvements and give back by issuing a pull request. 

You can even edit the docs online with GitHub, without ever downloading the files – the “Improve this Doc” button on any given page will direct you to GitHub’s online editor for that page.

You can find the documentation at https://github.com/cakemanager/cakephp-posttypes/tree/1.0/docs.

[doc_toc]

Markdown Standards
==================

Documentation is built with [Markdown](http://en.wikipedia.org/wiki/Markdown). This sections contains some standards we use to convert the documentation to our website.

Basic Writing
-------------

### Paragraphs

Paragraphs in Markdown are just one or more lines of consecutive text followed by one or more blank lines.

On July 2, an alien mothership entered Earth's orbit and deployed several dozen saucer-shaped "destroyer" spacecraft, each 15 miles (24 km) wide.

On July 3, the Black Knights, a squadron of Marine Corps F/A-18 Hornets, participated in an assault on a destroyer near the city of Los Angeles.

### Headings

You can create a heading by adding one or more # symbols before your heading text. The number of # you use will determine the size of the heading.

	# The largest heading (an <h1> tag)
	## The second largest heading (an <h2> tag)
	…
	###### The 6th largest heading (an <h6> tag)

When adding a new page you use the largest heading like this:

	My Subject
	==========
	
The title should be identical to the file-name and meaning of the page.

The page itself uses the second largest headings to build up a table of contents

	Section 1
	---------
	
	Section 2
	---------

By using this headings you are able to generate a table of contents via:

	[doc_toc]
	
Use this block at the beginning of your file, but after the heading and explanation about the section.

### Blockquotes

You can indicate blockquotes with a >. Blockquotes are used to create an alert to let the user know there's something special for that section

In the words of Abraham Lincoln:

	> Note: This feature is supported at version 1.4.0.


### Styling text

You can make text bold or italic.

	*This text will be italic*
	**This text will be bold**
	Both bold and italic can use either a * or an _ around the text for styling. This allows you to combine both bold and italic if needed.

	**Everyone _must_ attend the meeting at 5 o'clock today.**


Lists
-----

### Unordered lists

You can make an unordered list by preceding list items with either a * or a -.

	* Item
	* Item
	* Item

	- Item
	- Item
	- Item
	
### Ordered lists

You can make an ordered list by preceding list items with a number.

	1. Item 1
	2. Item 2
	3. Item 3
	
### Nested lists

You can create nested lists by indenting list items by two spaces.

	1. Item 1
	  1. A corollary to the above item.
	  2. Yet another point to consider.
	2. Item 2
	  * A corollary that does not need to be ordered.
		* This is indented four spaces, because it's two spaces further than the item above.
		* You might want to consider making a new list.
	3. Item 3

Code formatting
---------------

### Inline formats

Use single backticks (`) to format text in a special monospace format. Everything within the backticks appear as-is, with no other special formatting.

Here's an idea: why don't we take `SuperiorProject` and turn it into `**Reasonable**Project`.

### Multiple lines

You can use triple backticks (```) to format text as its own distinct block.

Check out this neat program I wrote:

	```
	x = 0
	x = 2 + 2
	what is x
	```
	
Links
-----

You can create an inline link by wrapping link text in brackets ( [ ] ), and then wrapping the link in parentheses ( ( ) ).

For example, to create a hyperlink to www.github.com, with a link text that says, Visit GitHub!, you'd write this in Markdown: [Visit GitHub!](www.github.com).

References
----------

This guide was inspired by https://help.github.com/articles/markdown-basics/.
