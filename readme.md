PropertiesToFile Snippet for MODx Revolution
=======================================

**Author:** Bob Ray [Bob's Guides](http://bobsguides.com)


PropertiesToFile writes the properties of an existing snippet or other element to a valid php file that can be used in a build script.
It puts the appropriate phpDoc header in the file and writes the correctly formatted file to the location you specify in the snippet parameters
or in the snippet's default properties. As far as I can tell, it handles all types of properties, including List types.

I tested it on Formit, and it appears to produce the same file used in Shaun's _build directory. It's handy if you prefer to create the properties
of an element to be placed in a transport package in the Manager rather than in code.

Typical usage:

    [[!PropertiesToFile?`

        &packageName = `FormIt`

        &subPackageName = `build`

        &fileName = `properties.formit.php`

        &elementName = `FormIt`

        &elementId = `16`

        &type = `modSnippet`

    ]]
