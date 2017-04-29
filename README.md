# Yireo CMS Layouts
This Magento module - Yireo\_CmsLayouts - allows you to create a HTML block with numerous elements. The layout of
these elements is determined through a theme-based mockup, which allows Magento backend-users to easily configure
the contents of these elements.

## Terminology
- Block: A Magento Static Block or CMS block.
- Mockup: A Yireo\_CmsLayouts block containing multiple elements
- Element: A simple HTML block containing multiple values

## Usage
This module is meant for webdevelopers to design a certain mockup of content, that can then be maintained
by non-technical people. The benefit here is that non-technical people can focus on solely the content, 
while the webdevelopers can focus on the design.

## Backend management
Within the backend, you can browse to CMS > CMS Layouts, to manage existing mockups or create new mockups.
Each mockup gets a mockup file, which is part of the Magento theme (both the backend theme and the frontend theme).
The mockup file adds a styled management page, for instance with a grid layout, that looks the same in the 
backend as in the frontend.

When a mockup is being edited in the backend, each element is shown within its own container. When clicked upon,
a modal popup opens up, which displays a simple form. Using that form, each element gets certain values, that are
afterwards displayed in both the backend mockup and the frontend mockup.

## Adding a mockup to a Magento block
To add an existing mockup with ID 1 to a Magento block, use the following tag:

    {{block type="cmslayouts/mockup" mockup_id="1"}}

To add an existing mockup with ID 1 using XML layout, use the following:

    <reference name="content">
        <block type="cmslayouts/mockup" name="example">
            <action method="setMockupId"><mockup_id>1</mockup_id></action>
        </block>
    </reference>

## Editing mockup files
The extension only comes with a few example mockups. For instance, the mockup `homepage` exists of the following files:

    app/design/adminhtml/default/default/template/cmslayouts/mockups/homepage.phtml
    app/design/adminhtml/default/default/template/cmslayouts/mockups/homepage.xml

The PHTML file contains a basic HTML structure. The XML file contains the logical structure, defining the various elements
that are shown in this mockup. Note that this XML file is not a XML Layout file.

To create new elements, you can add its XML definition to the XML file. Each element is styled according to its unique ID.
If there is an element with ID `advertizement`, its PHTML template will be:

    app/design/frontend/base/default/template/cmslayouts/element/advertizement.phtml

Note that this PHTML template is located in the frontend theme.

## Editing CSS
Within the backend, a `default.css` file is loaded to allow for the modal popups to work properly. Additionally,
the Magento module tries to load a specific CSS per mockup file. If you have created a mockup based on the mockup file
`homepage` (like in the example above), the CSS files that are loaded in the backend are:

    skin/adminhtml/default/default/css/cmslayouts/default.css
    skin/adminhtml/default/default/css/cmslayouts/homepage.css

In the frontend the CSS file is only this one:

    skin/frontend/base/default/css/cmslayouts/homepage.css

Naturally, all CSS files can be overridden in your own custom theme.

## Roadmap
* Allow easier editing of fields (product, image)
* Integrate PieCSS for grid in both frontend and backend
