php-json-schema-generator
======================

PHP JSON Schema Generator

## About JSON Schema

JSON has become a mainstay in the vast HTTP toolkit. In order to make it more stable and to increase the longevity of the data structure there must be some standards put into place.  These standards will help to define the structure in a way that the industry can rely on.  A uniform way to parse the data and interpret the meaning of the data elements can be found in building a schema that represents it.   XML already has protocols and many tools for defining these items. 

Due to the flexible nature of JSON a solid Schema definition has been slow coming.  At this time there is an internet draft being worked on.  
http://tools.ietf.org/html/draft-zyp-json-schema-04

The uses/pros/cons and other discussions related to what a schema can and cannot do for you are beyond the scope of this document.  

## About This Tool

It is a bit tedious to have to manually write out a JSON Schema every time a new REST point or Data object is created.  Because JSON can be used to represent a variety of data objects it can be helpful to have a dynamic way to map from one object to a JSON Schema. A php array is considered an object her for the sake of ease of communication.  

The goal of the tool is to provide an trivial implement for generating a JSON Schema off a dynamic range of objects.  

 