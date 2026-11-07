# API: `get_contacts_adif`

Incremental ADIF download of one station location, using a QSO-ID cursor so that
clients can sync new QSOs without downloading the whole logbook each time.

```
POST /index.php/api/get_contacts_adif      (or /api/get_contacts_adif when URL rewriting is enabled)
Content-Type: application/json
```

## Request

```json
{ "key": "YOUR_API_KEY", "station_id": 1, "fetchfromid": 0, "limit": 5000 }
```

| Field         | Required | Description |
|---------------|----------|-------------|
| `key`         | yes      | A Cloudlog API key (Admin → API Keys) that is **active** and has **read** (`r`) or read/write (`rw`) rights, and that belongs to a user. |
| `station_id`  | yes      | A single positive integer: the **Cloudlog station location ID** (`station_profile.station_id`, the ID shown in *Station Locations*). In Cloudlog this is the station location. It must be owned by the API key's user. Arrays are not supported. |
| `fetchfromid` | no (0)   | Non-negative integer cursor. Only QSOs whose internal primary key (`COL_PRIMARY_KEY`) is **strictly greater** are returned. |
| `limit`      | no       | Page size, 1 – 10000. Default 5000. `-1` ("unlimited") is **not** supported. |

`station_id`, `fetchfromid` and `limit` may be JSON integers or canonical decimal
strings (`"12"`). Decimals, exponents, signs, leading zeros, booleans, arrays,
objects and out-of-range values are rejected with `400`. Unknown fields are ignored.

### API key sources

Cloudlog's existing API convention is the key in the JSON body (`api/qso`) or the
URL path; it has no header-based API key. This endpoint therefore reads the key
**only from the JSON field `key`**, so there is no precedence or conflict to resolve.
The key is never logged or echoed. API key validation is `Api_model::authorize()`
(active status, `r`/`rw` rights); keys with no owning user are rejected. Cloudlog
API keys have no expiry and no station binding, so neither exists here.

## Response

`200 OK`, `Content-Type: application/json; charset=utf-8`, `Cache-Control: no-store`

```json
{
  "status": "success",
  "exported_qsos": 5,
  "lastfetchedid": 4886,
  "message": "Export successful",
  "station_id": 123,
  "limit": 5000,
  "has_more": false,
  "adif": "Cloudlog ADIF export\r\n<ADIF_VER:5>3.1.4\r\n...<EOH>\r\n\r\n<CALL:5>K1ABC ... <EOR>\r\n\r\n"
}
```

* `exported_qsos` – QSOs in this response; `lastfetchedid` – highest QSO ID in it,
  or the incoming `fetchfromid` when nothing matched. Send it as `fetchfromid` next time.
* `has_more` – more QSOs exist after this page; request again until `false`.
* `adif` – complete ADIF document (header, `<EOH>`, one `<EOR>` per QSO), produced by
  the same `AdifHelper` as the other Cloudlog ADIF exports. With no new QSOs it is a header only.

### Errors

Cloudlog's existing API error shape, with `message` duplicated from `reason`:

```json
{ "status": "failed", "reason": "Missing or invalid API key", "message": "Missing or invalid API key" }
```

| HTTP | Meaning |
|------|---------|
| 400  | Malformed JSON, body > 4 KB, or invalid `station_id` / `fetchfromid` / `limit` |
| 401  | Missing, invalid, disabled or ownerless API key |
| 403  | Station does not exist **or** is not owned by the key's user (indistinguishable) |
| 405  | Not POST (`Allow: POST`) |
| 415  | Content-Type is not `application/json` |
| 500  | Unexpected error (generic message; details only in the server log) |

## Security model

* The key is validated before any QSO query; the station is then checked with
  `Stations::check_station_against_user()`, and the SQL itself is scoped by
  `station_id` **and** `station_profile.user_id`, so a cursor that is valid in another
  station or user's log can never return that data.
* Only stations **owned** by the key's user are exportable. Stations merely shared with
  the user through a shared logbook are not (`403`).
* Any PHP notice/warning output is discarded, so error and success bodies are always pure JSON.

## Synchronisation limitations

The cursor is the QSO primary key. The protocol therefore only discovers **newly added**
QSOs. Edits to, or deletions of, existing QSOs are not reported, and QSOs imported with an
older primary key than your cursor are not seen. Cloudlog has no change log that could
safely be exposed here, so none is invented. Do a full re-sync (`fetchfromid: 0`) if you need to detect such changes.

## Manual example:

```
curl -s -X POST http://localhost/index.php/api/get_contacts_adif \
  -H 'Content-Type: application/json' \
  -d '{"key":"YOUR_API_KEY","station_id":1,"fetchfromid":0}'
```
