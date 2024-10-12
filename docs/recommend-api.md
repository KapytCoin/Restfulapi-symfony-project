# Recommendation API

GET /api/v1/product/{product_id}/recommendations
Authorization: Bearer {token}

## 200

```json
{
    "id": 1,
    "ts": 123213123,
    "recommendations": [
        {"id": 1}
    ]
}
```

## 403

```json
{
    "error": "access denied"
}
```