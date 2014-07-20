Funkylab 5

###Features :

	* easy to translate
	* extendable by plugin (packagist or external personnal server)
	* easy to add feature
	* plugins 
		- modules (acces by role) (funkylab 3)
			- configurable by xml, yaml, etc..
			- launch personnal code
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

				- tree (funkylab 3 et 4)
					- order
					- dragndrop
					- edit in place

		- system (funkylab 1)

			- maintenance
				- clear cache
				- export database
				- import database
				- do something tricky

			- user (datagrid)
				- role
				- FOSBundle
				- configuration
					- admin css
					- localisation
					- password etc..
					- role

			- file manager 
				- multiupload
				- preview (all file format supported)
				- zip
				- unzip

			- language
				- upload location file

		=== A REPENSER ===
		=== peut etre en faire le premier plugin ===

		- mode website (funkylab2)
			- billet (funkylab 2)
				- format
				- contenu
					- plugins
						- images
						- text
						- seo
						- contenu from plugin (see up)

			- menus (funkylab 2)	
				- unlimited menu
					- available in twig

			- forms (funkylab 5)
				- unlimited menu

			- configuration (funkylab 2)
				- cache
				- clear cache
				- select template
				- activation website mode
				- localisation configuration
				- stats

###Tools
	* from extjs
	* generated
	* extend twig

###Commandline
	* generate interface
	* add plugin
		- paramters.yml
		- module.xml
		- all in commande ex :
			$ ./app/console funkylab:plugins:add toto --type=datagrid --entity=post
	* remove plugin
		- ex:
			$ ./app/console funkylab:plugins:add toto --cached (keep generated file)

###Install
	* A simple SF2 bundle

###Usage : 
	* Generate all type of admin & tools
	* Generate a tool to edit page online, and specifiy a type of content
