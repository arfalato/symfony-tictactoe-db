# symfony-tictactoe-db
symfony tic-tac-toe game with DB

ENVIRONMENT
- LAMP and composer

INSTALLATION
- clone/get repository content
- run "docker-compose up -d"

DEFAULT HOST  
```
localhost:8080
```  

API ENDPOINTS PROVIDED

- to create a board
``` 
POST /api/board
``` 
- to destroy a board
``` 
DELETE /api/board/{id}
``` 
- to make a move
``` 
PUT /api/board/{id}
{
  "symbol": {X|O},
  "row": int,
  "column": int
}
``` 
GAME EXAMPLE

- CREATE BOARD
``` 
curl -X POST \
  http://localhost:8080/api/board \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json'

Response
{
    "Board": [
        [
            null,
            null,
            null
        ],
        [
            null,
            null,
            null
        ],
        [
            null,
            null,
            null
        ]
    ],
    "id": 10
}
``` 
MOVES (Notice: X moves first by design)
``` 
curl -X PUT \
  http://localhost:/api/board/10 \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{
  "symbol":"X",
  "row":1,
  "column": 0
  }'

Response
{
    "Board": [
        [
            null,
            null,
            null
        ],
        [
            "X",
            null,
            null
        ],
        [
            null,
            null,
            null
        ]
    ]
}
``` 
``` 
curl -X PUT \
  http://localhost:8080/api/board/10 \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{
  "symbol":"O",
  "row":1,
  "column": 1
  }'

Response
{
    "Board": [
        [
            null,
            null,
            null
        ],
        [
            "X",
            "O",
            null
        ],
        [
            null,
            null,
            null
        ]
    ]
}
``` 
``` 
curl -X PUT \
  http://localhost:8080/api/board/10 \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{
  "symbol":"X",
  "row":2,
  "column": 0
  }'

Response
{
    "Board": [
        [
            null,
            null,
            null
        ],
        [
            "X",
            "O",
            null
        ],
        [
            "X",
            null,
            null
        ]
    ]
}
``` 
``` 
curl -X PUT \
  http://localhost:8080/api/board/10 \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{
  "symbol":"O",
  "row":2,
  "column": 1
  }'

Response
{
    "Board": [
        [
            null,
            null,
            null
        ],
        [
            "X",
            "O",
            null
        ],
        [
            "X",
            "O",
            null
        ]
    ]
}
``` 
``` 
curl -X PUT \
  http://localhost:8080/api/board/10\
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -d '{
  "symbol":"X",
  "row":0,
  "column": 0
  }'

Response
{
    "winner": "X",
    "Board": [
        [
            "X",
            null,
            null
        ],
        [
            "X",
            "O",
            null
        ],
        [
            "X",
            "O",
            null
        ]
    ]
}
``` 
DESTROY BOARD
``` 
curl -X DELETE \
  http://localhost:8080/api/board/10 \
  -H 'cache-control: no-cache'

Response
{
    "Board": "deleted",
    "id": 10
}

``` 

RUN UNIT TESTS FROM DOCKER CLI  
``` 
root@5c1bc2558b8d:/var/www/html# php bin/phpunit
PHPUnit 8.5.28 

....................                                              20 / 20 (100%)

Time: 807 ms, Memory: 6.00 MB

OK (20 tests, 20 assertions)
```   

WHAT MISSED
- One player game VS computer
