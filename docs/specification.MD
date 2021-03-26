# Specification

The specification for this repository will use the user story format from GOV.UK:

[https://www.gov.uk/service-manual/agile-delivery/writing-user-stories](https://www.gov.uk/service-manual/agile-delivery/writing-user-stories)

The following are user stories that informed the creation of the API:

## User story 1

* As a charity website user
* I want to search using my postcode
* So that I can find a office near me

* it’s done when the user can search using their postcode
* it’s done when offices near their postcode are displayed

## User story 2

* As a charity website user
* I want to search using my postcode
* So that I can find services in my area

* it’s done when the user can search using their postcode
* it’s done when services in their town/city are displayed

## User story 3

* As a charity website user
* I want to type a search
* So that I can find resources I am interested in

* it’s done when the user can search using plain text
* it’s done when resources that match that term are returned

# Scope

The scope of this project is to create an architecture to allow charities to perform searches without having to code to a specific 3rd party. The 3rd party can be changed without the architecture needing to be changed.

This requires creating general ingress points for data input, and search APIs for the output.

Wrappers to connect 3rd party APIs to the open api defined here will created.