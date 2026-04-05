# RemPlanner API Server

API сервер для управления этажами и планировками в приложении RemPlanner.

## 📋 Эндпоинты

### 1. Добавление нового этажа

**Запрос:**
```
POST /setup/api/planner/floor_add_new/
Content-Type: application/json

{
  "object_id": "5533608",
  "plan_id": 636643,
  "level": [1, 2],
  "floor_uid": "floor_1"
}
```

**Ответ (успех):**
```json
{
  "status": "success",
  "floor_id": 305491,
  "plan_id": 636643,
  "on_back_floor": false
}
```

**Ответ (ошибка):**
```json
{
  "status": "error",
  "error": "Invalid object_id"
}
```

---

### 2. Добавление новой планировки

**Запрос:**
```
POST /setup/api/planner/plan_add_new/
Content-Type: application/json

{
  "object_id": "5533608",
  "plan_id": -1,
  "level": 1,
  "label": "Спальня"
}
```

**Ответ (успех):**
```json
{
  "status": "success",
  "plan_id": 636644,
  "label": "Спальня",
  "level": 1
}
```

**Ответ (ошибка):**
```json
{
  "status": "error",
  "error": "Label is required (1-100 characters)"
}
```

---

### 3. Получение всех планировок проекта (БОНУС)

**Запрос:**
```
GET /setup/api/planner/plans/:object_id
```

**Ответ:**
```json
{
  "status": "success",
  "plans": [
    {
      "plan_id": 636643,
      "object_id": "5533608",
      "level": 1,
      "label": "Гостиная",
      "created_at": "2026-04-05T10:30:00.000Z"
    }
  ]
}
```

---

### 4. Получение всех этажей планировки (БОНУС)

**Запрос:**
```
GET /setup/api/planner/floors/:plan_id
```

**Ответ:**
```json
{
  "status": "success",
  "floors": [
    {
      "floor_id": 305491,
      "plan_id": 636643,
      "object_id": "5533608",
      "level": [1, 2],
      "floor_uid": "floor_1",
      "created_at": "2026-04-05T10:30:00.000Z"
    }
  ]
}
```

---

## 🚀 Установка и запуск

```bash
# Установка зависимостей
npm install

# Запуск сервера (production)
npm start

# Запуск с автоперезагрузкой (development)
npm run dev
```

Сервер будет доступен на `http://localhost:3000`

---

## 🧪 Тестирование

### Через curl

```bash
# Добавление планировки
curl -X POST http://localhost:3000/setup/api/planner/plan_add_new/ \
  -H "Content-Type: application/json" \
  -d '{
    "object_id": "5533608",
    "plan_id": -1,
    "level": 1,
    "label": "Гостиная"
  }'

# Добавление этажа
curl -X POST http://localhost:3000/setup/api/planner/floor_add_new/ \
  -H "Content-Type: application/json" \
  -d '{
    "object_id": "5533608",
    "plan_id": 636643,
    "level": [1, 2],
    "floor_uid": "floor_1"
  }'

# Получение планировок
curl http://localhost:3000/setup/api/planner/plans/5533608
```

---

## 📝 Параметры запросов

### floor_add_new

| Параметр | Тип | Обязательный | Описание |
|----------|-----|-------------|---------|
| `object_id` | string/number | Да | ID проекта |
| `plan_id` | number | Да | ID планировки (или -1 для новой) |
| `level` | number/array | Да | Номер этажа (1-100) или массив номеров |
| `floor_uid` | string | Да | Уникальный тип этажа |

### plan_add_new

| Параметр | Тип | Обязательный | Описание |
|----------|-----|-------------|---------|
| `object_id` | string/number | Да | ID проекта |
| `plan_id` | number | Да | ID существующей планировки (-1 для новой) |
| `level` | number | Да | Номер этажа (1-100) |
| `label` | string | Да | Название планировки (1-100 символов) |

---

## 🔧 Структура кода

- **Валидация** — все входные параметры проверяются
- **Обработка ошибок** — все исключения обрабатываются и возвращаются в JSON
- **In-memory storage** — данные хранятся в памяти (для продакшена используйте БД)
- **CORS включен** — API доступен с других доменов