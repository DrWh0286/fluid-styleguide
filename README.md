# EXT:fluid_styleguid

## Requirements

ext:fluid_components (sitegeist/fluid-components)

This extension is the base for the rendering process. For more information about usage read the documentation.

## Installation

1. Add this repository url to root composer.json repositories section.

2. Install composer package:

````bash
$ composer require pluswerk/fluid-styleguide
````
## Basic Usage

### Register template extension

Add the following lines to you template extension ext_localconf.php file:
```php
<?php
\Pluswerk\FluidStyleguide\Utility\StyleguideManagementUtility::registerForStyleguide('my_extension', 'VENDOR');
```
This will register the Needed Folders for Styleguide as a fluid_component folder (you do not have to do the
registration, which is shown at the fluid_components documentation!).

The base file path to where the styleguide files should found in your extension is `Resources/Private/Styleguide`.
If you want to change this, you have to add this path as third argument to the register method:
````php
<?php
\Pluswerk\FluidStyleguide\Utility\StyleguideManagementUtility::registerForStyleguide(
    'my_extension',
    'VENDOR',
    'my/path/to/styleguide'
);
````
### Create Folders for the components

To create Atoms, Molecules, Organisms (, Templates or Pages) you have to add the corresponding folder to the styleguide
folder (`my_extension/Resources/Styleguide/`; see above how to change this).

So your folder structure should look like this:

`my_extension/Resources/Styleguide/Atoms`

`my_extension/Resources/Styleguide/Molecules`

`my_extension/Resources/Styleguide/Organisms`

`my_extension/Resources/Styleguide/Templates`

`my_extension/Resources/Styleguide/Pages`

All these folders are registered to fluid_components, so the can be used like the components folder in fluid_components
documentation.

To avoid the namespace thing on top of each html file, some viewhelper shortcuts are registered globally for these folders:
````html
<at:myAtom>
<mo:myMolecule>
<or:myOrganism>
<te:myTemplate>
<pa:myPage>
````
This means, if you create fluid components you can use them everywhere in your TYPO3 installation.

### Dummy data for styleguide

To give dummy data to the components to be rendered in the styleguide a json file is needed.

So to give the file

`my_extension/Resources/Styleguide/Atoms/BaseElements/Headline/Headline.html`

dummy data the file

`my_extension/Resources/Styleguide/Atoms/BaseElements/Headline/Headline.json`

has to be present! How this json file has to look like see th example section

### Example:

Assume we have the following constellation:

`my_extension/Resources/Styleguide/Atoms/BaseElements/Headline/Headline.html`

`my_extension/Resources/Styleguide/Atoms/BaseElements/Headline/Headline.json`

`my_extension/Resources/Styleguide/Atoms/BaseElements/Text/Text.html`

`my_extension/Resources/Styleguide/Atoms/BaseElements/Text/Text.json`

`my_extension/Resources/Styleguide/Molecules/ContenElements/SimpleTextElement/SimpleTextElement.html`

`my_extension/Resources/Styleguide/Molecules/ContenElements/SimpleTextElement/SimpleTextElement.json`

(The file has always to be located in a folder with the same name -> look into fluid_components documentation!)

__Headline.html__
````html
<fc:component>
  <fc:param name="headline" type="string" />
  <fc:param name="level" type="integer" >2</fc:param>

  <fc:renderer>
    <f:switch expression="{level}">
      <f:case value="1">
        <h1>{headline}</h1>
      </f:case>
      <f:case value="2">
        <h2>{headline}</h2>
      </f:case>
      <f:case value="3">
        <h3>{headline}</h3>
      </f:case>
      <f:case value="4">
        <h4>{headline}</h4>
      </f:case>
      <f:case value="5">
        <h5>{headline}</h5>
      </f:case>
      <f:case value="6">
        <h6>{headline}</h6>
      </f:case>
    </f:switch>
  </fc:renderer>
</fc:component>
````
__Text.html__
````html
<fc:component>
  <fc:param name="text" type="string" />
  <fc:renderer>
    <f:if condition="{text}">
      <p>{text}</p>
    </f:if>
  </fc:renderer>
</fc:component>
````
__SimpleTextElement.html__
````html
<fc:component>
  <fc:param name="headline" type="string" />
  <fc:param name="headlineLevel" type="integer" />
  <fc:param name="text" type="string" />
  <fc:renderer>
    <at:baseElements.headline headline="{headline}" level="{headlineLevel}"/>
    <at:baseElements.text text="{text}"/>
  </fc:renderer>
</fc:component>
````
The SimpleTextElement.html component can be used everywhere (also in the styleguide again) e.g. with the following code:
```html
<mo:contenElements.simpleTextElement headline="My headline" headlineLevel="3" text="my text" />
```
This will render the component SimpleTextElement with the given parameters.

To render these components in the json file could look like this:

__Headline.json__
```json
{
  "data": [
    {
      "headline": "headline level 1",
      "level": 1
    },
    {
      "headline": "headline level 2",
      "level": 2
    },
    {
      "headline": "headline level 3",
      "level": 3
    },
    {
      "headline": "headline level 4",
      "level": 4
    },
    {
      "headline": "headline level 5",
      "level": 5
    },
    {
      "headline": "headline level 6",
      "level": 6
    }
  ]
}
```

__Text.json__

```json
{
  "data": [
    {
      "text": "This is a test text from dummy json!"
    }
  ]
}
```
__SimpleTextElement.json__
````json
{
  "data": [
    {
      "headline": "This is a simple text element",
      "headlineLevel": 2,
      "text": "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet."
    }
  ]
}
````
Each json object in the `data` array causes a rendering in the styleguide.

### Print out in the frontend

To print out the styleguide in the frontend, just add the Styleguide TYPO3 plugin to a normal page.

If you want to remove the page layout of the normal page and print out the plane styleguide you have
to add a Template record to this page and override your page template.

__Example:__

Setup section:

```
page.10 >
page.10 =< styles.content.get
```