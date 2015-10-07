Funkylab 5

###Features :

	* plugins 
			- generated from doctrine information
				- table (datagrid) funkylab 4
					- list
						- format (text, number, image thumb)
					- pagination
					- sort
					- edit in place (text, boolean)
					- edit form
						- fields :
							- combobox
							- file
							- htmleditor
							- multiselect
							- selectlist
							- textarea
							- textfield (password, email, number, range)
					- export (pdf, csv, html, xml)
          - form singleton
          - relation grid
          
				- tree (funkylab 3 et 4)
					- order
					- dragndrop
					- edit in place

		- system (funkylab 1)

			- file manager 
				- multiupload
				- preview (all file format supported)
				- zip
				- unzip

		=== A REPENSER ===
		=== peut etre en faire le premier plugin ===

			- menus (funkylab 2)	
				- unlimited menu
					- available in twig

			- forms (funkylab 5)
				- unlimited menu

			- configuration (funkylab 2)
				- select template
				- activation website mode maintenance
				- stats

###Tools
	* from extjs
	* generated
	* extend twig

###Commandline
	* generate interface
	* crud
		- all in commande ex :
			$ ./app/console funkylab:plugins:add toto --type=datagrid --entity=post

