const originPredictions = 
[
  {
      "description": "Harvard University, Oxford Street, Cambridge, MA, USA",
      "matched_substrings": [
          {
              "length": 7,
              "offset": 0
          }
      ],
      "place_id": "ChIJmQF_fUJ344kR8Cck7lzGN1k",
      "reference": "ChIJmQF_fUJ344kR8Cck7lzGN1k",
      "structured_formatting": {
          "main_text": "Harvard University",
          "main_text_matched_substrings": [
              {
                  "length": 7,
                  "offset": 0
              }
          ],
          "secondary_text": "Oxford Street, Cambridge, MA, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Harvard University"
          },
          {
              "offset": 20,
              "value": "Oxford Street"
          },
          {
              "offset": 35,
              "value": "Cambridge"
          },
          {
              "offset": 46,
              "value": "MA"
          },
          {
              "offset": 50,
              "value": "USA"
          }
      ],
      "types": [
          "university",
          "university",
          "point_of_interest",
          "establishment"
      ]
  },
  {
      "description": "Harvard Square, Brattle Street, Cambridge, MA, USA",
      "matched_substrings": [
          {
              "length": 7,
              "offset": 0
          }
      ],
      "place_id": "ChIJecplvEJ344kRdjumhjIYylk",
      "reference": "ChIJecplvEJ344kRdjumhjIYylk",
      "structured_formatting": {
          "main_text": "Harvard Square",
          "main_text_matched_substrings": [
              {
                  "length": 7,
                  "offset": 0
              }
          ],
          "secondary_text": "Brattle Street, Cambridge, MA, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Harvard Square"
          },
          {
              "offset": 16,
              "value": "Brattle Street"
          },
          {
              "offset": 32,
              "value": "Cambridge"
          },
          {
              "offset": 43,
              "value": "MA"
          },
          {
              "offset": 47,
              "value": "USA"
          }
      ],
      "types": [
          "tourist_attraction",
          "point_of_interest",
          "establishment"
      ]
  },
  {
      "description": "Harvard, IL, USA",
      "matched_substrings": [
          {
              "length": 7,
              "offset": 0
          }
      ],
      "place_id": "ChIJo2ewqo5aD4gRxmLcqPIFHK8",
      "reference": "ChIJo2ewqo5aD4gRxmLcqPIFHK8",
      "structured_formatting": {
          "main_text": "Harvard",
          "main_text_matched_substrings": [
              {
                  "length": 7,
                  "offset": 0
              }
          ],
          "secondary_text": "IL, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Harvard"
          },
          {
              "offset": 9,
              "value": "IL"
          },
          {
              "offset": 13,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  },
  {
      "description": "Harvard Business School, Boston, MA, USA",
      "matched_substrings": [
          {
              "length": 7,
              "offset": 0
          }
      ],
      "place_id": "ChIJMSSJL9V544kRg_KzBuTuEY8",
      "reference": "ChIJMSSJL9V544kRg_KzBuTuEY8",
      "structured_formatting": {
          "main_text": "Harvard Business School",
          "main_text_matched_substrings": [
              {
                  "length": 7,
                  "offset": 0
              }
          ],
          "secondary_text": "Boston, MA, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Harvard Business School"
          },
          {
              "offset": 25,
              "value": "Boston"
          },
          {
              "offset": 33,
              "value": "MA"
          },
          {
              "offset": 37,
              "value": "USA"
          }
      ],
      "types": [
          "university",
          "university",
          "point_of_interest",
          "establishment"
      ]
  },
  {
      "description": "Harvard, MA, USA",
      "matched_substrings": [
          {
              "length": 7,
              "offset": 0
          }
      ],
      "place_id": "ChIJgc2QALjs44kRDEOT7Yy7kko",
      "reference": "ChIJgc2QALjs44kRDEOT7Yy7kko",
      "structured_formatting": {
          "main_text": "Harvard",
          "main_text_matched_substrings": [
              {
                  "length": 7,
                  "offset": 0
              }
          ],
          "secondary_text": "MA, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Harvard"
          },
          {
              "offset": 9,
              "value": "MA"
          },
          {
              "offset": 13,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  }
];

const destinationPredictions = 
[
  {
      "description": "Oxford, MS, USA",
      "matched_substrings": [
          {
              "length": 6,
              "offset": 0
          }
      ],
      "place_id": "ChIJ2aGn8G97gIgRHa_bMjKb5HY",
      "reference": "ChIJ2aGn8G97gIgRHa_bMjKb5HY",
      "structured_formatting": {
          "main_text": "Oxford",
          "main_text_matched_substrings": [
              {
                  "length": 6,
                  "offset": 0
              }
          ],
          "secondary_text": "MS, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Oxford"
          },
          {
              "offset": 8,
              "value": "MS"
          },
          {
              "offset": 12,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  },
  {
      "description": "Oxford, OH, USA",
      "matched_substrings": [
          {
              "length": 6,
              "offset": 0
          }
      ],
      "place_id": "ChIJM5K7Jw49QIgRzK96D3sHT2k",
      "reference": "ChIJM5K7Jw49QIgRzK96D3sHT2k",
      "structured_formatting": {
          "main_text": "Oxford",
          "main_text_matched_substrings": [
              {
                  "length": 6,
                  "offset": 0
              }
          ],
          "secondary_text": "OH, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Oxford"
          },
          {
              "offset": 8,
              "value": "OH"
          },
          {
              "offset": 12,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  },
  {
      "description": "Oxford, AL, USA",
      "matched_substrings": [
          {
              "length": 6,
              "offset": 0
          }
      ],
      "place_id": "ChIJw-IeJh-xi4gRfXcDpIutcrg",
      "reference": "ChIJw-IeJh-xi4gRfXcDpIutcrg",
      "structured_formatting": {
          "main_text": "Oxford",
          "main_text_matched_substrings": [
              {
                  "length": 6,
                  "offset": 0
              }
          ],
          "secondary_text": "AL, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Oxford"
          },
          {
              "offset": 8,
              "value": "AL"
          },
          {
              "offset": 12,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  },
  {
      "description": "Oxford, NC, USA",
      "matched_substrings": [
          {
              "length": 6,
              "offset": 0
          }
      ],
      "place_id": "ChIJIwmPVrSmrYkR6_EdGmUBRjc",
      "reference": "ChIJIwmPVrSmrYkR6_EdGmUBRjc",
      "structured_formatting": {
          "main_text": "Oxford",
          "main_text_matched_substrings": [
              {
                  "length": 6,
                  "offset": 0
              }
          ],
          "secondary_text": "NC, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Oxford"
          },
          {
              "offset": 8,
              "value": "NC"
          },
          {
              "offset": 12,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  },
  {
      "description": "Oxford, PA, USA",
      "matched_substrings": [
          {
              "length": 6,
              "offset": 0
          }
      ],
      "place_id": "ChIJXfHhCa5MxokRDLZtLjtkrUM",
      "reference": "ChIJXfHhCa5MxokRDLZtLjtkrUM",
      "structured_formatting": {
          "main_text": "Oxford",
          "main_text_matched_substrings": [
              {
                  "length": 6,
                  "offset": 0
              }
          ],
          "secondary_text": "PA, USA"
      },
      "terms": [
          {
              "offset": 0,
              "value": "Oxford"
          },
          {
              "offset": 8,
              "value": "PA"
          },
          {
              "offset": 12,
              "value": "USA"
          }
      ],
      "types": [
          "locality",
          "political",
          "geocode"
      ]
  }
];

// export default placePredictions;