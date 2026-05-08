# Scrappy Dish | Backend [BETA]

This app provides recipe suggestions based on available ingredients and other customized preset filters. The recipes suggestions are powered by Hermes AI.

---

## Features

- Public endpoint for suggesting single and multiple recipes.
- Users can save their recipes when they create an account (through Google).
- Suggest recipes based on variuos filters for better results.

---

## Public Endpoints

### AI Suggestions

#### POST : `/api/recipes/suggest-single`

This endpoint returns a suggested single recipe based on user's available ingredients and equipments.

**Request**

```
available_ingredients: string[], required,
available_equipments: string[]
```

#### POST : `/api/recipes/suggest-multiple`

This endpoint returns suggested recipes based on user's customized parameters.

**Request**

```
available_ingredients: string[], required,
dietary_preferences: string[],
cuisine_preferences: string[],
dish_preferences: string[],
available_equipments: string[],
cook_time_minutes: integer,
difficulty: integer, min:1, max:10,
servings: integer, min:1,
additional_instructions: string
```

---

## Private Endpoints

These endpoints require a valid auth token to be attached to the header as bearer token for each request.

### Authentication

#### POST : `/api/auth/google`

This endpoint returns the user's info with an auth token after google validation. It expects an id token from the firebase google auth. This is used for both google sign-up and sign-in.

**Request**

```
id_token: string, required
```

#### GET : `/api/auth/me`

This endpoint checks the current user if it's verified or not. It returns status code `200` if authenticated, `401` otherwise.

#### GET : `/api/logout`

This endpoint deletes the user's current token.

#### GET : `/api/logout-all`

This endpoint deletes all existing tokens of the user.

### User

#### GET : `/api/user`

This endpoint returns the current user's info.

#### PATCH: `/api/user`

This endpoint partially update the user based on the parameters given.

**Request**

```
name: string,
avatar: string
```

#### DELETE : `/api/user`

This endpoint deletes the current user and all its owned recipes and tokens.

### Recipe

#### POST : `/api/recipes/save`

This endpoint saves the recipe for the user.

**Request**

```
title: string, required
description: string, required
ingredients_used: string[], required,
steps: string[], required,
cook_time_minutes: integer, required,
difficulty: integer, min:1, max:10, required,
serbings: integer, min:1, required,
cuisine_tags: string[], required,
dish_tags: string[], required,
general_tags: string[], required,
nutrition_notes: string, required
```

#### GET : `/api/recipes{id}`

This endpoint returns a detailed saved recipe owned by the user.

#### GET : `/api/recipes?`

**Params**:

```
page: integer, required
per_page: integer, min:15, max:50, required
```

#### DELETE : `/api/recipes{id}`

This endpoint deletes a specified recipe of the user.

---

Developer: Kian Jacob Anthony Tubalinal
