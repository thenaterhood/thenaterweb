<?php

/**
 * This is a meta-file for registering extensions with the engine 
 * Extensions are registered by importing them in this file. 
 *
 * All extensions should be self-contained in their own class for 
 * communicating with the engine and should implement the 
 * engine extension class, as that is how the engine expects to 
 * communicate with them. The engine and builtin pages allow 
 * extensions to add code at the beginning or the end of any page. 
 * The pages or files that will use extensions must register them 
 * independently. To apply an extension to a full area of the site, 
 * edit the index for that area to register the extension. To 
 * apply an extension to individual pages, register them on the 
 * page itself, although this may require a little more work - but 
 * additional methods can be implemented and used on a more granular 
 * level on each page. Extensions are passed the session object
 * when they are initialized but may make use of their own session
 * objects as well if need be. Extensions can be stored anywhere
 * providing they can access the Extension interface and the 
 * path to them is kept up to date here.
 *
 * @since 7/26/13
 * @author Nate Levesque <public@thenaterhood.com>
 */




?>
