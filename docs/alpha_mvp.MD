# Alpha MVP functionality

From the user stories the following functionality will be created for the MVP:

## Postcode / location lookup

A service the wraps 3rd party postcode / location lookup sevices to convert a postcode / generic location into a standard [location format](https://schema.org/Place):

```
{
  "@context": "https://schema.org",
  "@type": "Place",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Great Russell Street",
    "addressLocality": "London",// City or Town
    "addressRegion": "London", // 1st level region
    "postalCode": "WC1B 3DG",
    "addressCountry" : "UK"
    "latitude": "40.75",
    "longitude": "73.98"
  },
}
```

## Data ingress

To add / update / delete data to the search index, a data format needs to be defined:

### Location input

```
{
  "@context": "https://schema.org",
  "@type": "Place",
  "description" : "Description of location",
  "url" : "http://",
  "photo" : {
    "@type": "ImageObject",
    "contentUrl": "photo.jpg",
    "description": "Description of image",
  },
  "maximumAttendeeCapacity" : 5,
  "openingHoursSpecification" :
    [
      "@type": "OpeningHoursSpecification",
      "opens":  "09:00:00",
      "closes":  "17:00:00",
      "dayOfWeek": "https://schema.org/Sunday"
    },
    {
      "@type": "OpeningHoursSpecification",
      "opens": "09:00:00",
      "closes": "17:00:00" ,
      "dayOfWeek": "https://schema.org/Saturday"
    },
    ]
  },
  "aggregateRating" : {
    "@type": "AggregateRating",
    "ratingValue": "4",
    "reviewCount": "250"
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Great Russell Street",
    "addressLocality": "London",
    "addressRegion": "London",
    "postalCode": "WC1B 3DG",
    "addressCountry" : "UK"
  }
}
```

### Content input

```
{
  "@context": "https://schema.org",
  "@type": "Article",
  "author": "John Doe",
  "abstract" : "",
  "articleBody" : "",
  "publisher": "Charity",
  "aggregateRating" : {
    "@type": "AggregateRating",
    "ratingValue": "4",
    "reviewCount": "250"
  },
  "datePublished" : "2009-05-08",
  "keywords" : [
    "word1",
    "word2"
  ],
  "thumbnailUrl" : "https://",
  "hasPart" : [
    {
      "@type": "CreativeWork",
      "audio" : {
        "@type" : "AudioObject",
        "encodingFormat": "audio/mpeg",
        "contentUrl": "12oclock_girona.mp3"
      }
    },
    {
      "@type": "CreativeWork",
      "video" : {
        "@type": "VideoObject"
        "thumbnail": "https://thumbnail.jpg",
        "encodingFormat": "video/mp4",
        "contentUrl" : "https://video.mp4"
      }
    }
  ]
}
```

These endpoints need to be secured so only the charity can make these changes.

## Search endpoint

When a loction is searched for the return data will be in the same format as the input data (the same object will be returned).

For the text based search, the articles will be returned in the same format they are entered.

# Future proofing

Using the Schema.org schemas for each content type, the system can be added to easily to faciliate more complex searches / filters in future.

Returning the document that was entered into the search originally maintains maximum flexbility and keeps the open schema format throughout the system.