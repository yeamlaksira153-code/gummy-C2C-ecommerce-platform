# GUMMY Marketplace: Website Structure and Code Logic

This document explains how the website is organized and how the main code paths work.

## 1) High-Level Architecture

The project is a PHP + MySQL marketplace application with server-rendered pages.

- Backend language: PHP (mostly page-controller style)
- Database: MySQL (via PDO)
- Frontend: HTML/CSS/vanilla JS in each page
- Session auth: `$_SESSION` is used for login state and roles
- Media/files: uploaded ID and listing images stored in `images/`

### Main runtime pattern

Each page usually does this in one file:

1. `session_start()`
2. include `pages/db.php`
3. process POST/GET actions (create/update/read/delete)
4. render HTML UI

## 2) Folder and File Structure

### Root

- `database.sql`: canonical schema (tables, roles, seeded manager)
- `alter_orders.php`: migration/maintenance script for orders (if used)
- `secret.env`: environment variables (DB + admin/manager fallback credentials)
- `data/`: JSON datasets used for simulation or fallback-like behavior
  - `delivery.json`: courier tracking simulation data
  - `listings.json`, `users.json`: legacy/static data artifacts
- `images/`
  - `ids/`: uploaded user ID documents
  - `listings/`: uploaded listing images

### `pages/`

- `db.php`: loads env vars and creates PDO connection
- `index.php`: homepage + navigation/entry points
- `alllistings.php`: all active listings from DB
- `casualtraders.php`: casual seller listings page
- `informaltraders.php`: informal seller listings page
- `sell.php`: create new listing (casual/informal flow)
- `buy.php`: listing detail and buy modal path
- `checkout_new.php`: delivery method selection + order creation
- `fake_payment_new.php`: simulated payment gateway and escrow updates
- `trackorder.php`: active order tracking + confirmation actions
- `orders.php`: buyer order history and escrow release flow
- `messages.php`: per-listing buyer/seller messaging
- `mylistings.php`: seller edits/deletes own listings
- `profile.php`: profile update + ID upload/verification status
- `setup.php`: one-time DB/table setup helper

### `pages/auth/`

- `register.php`: account creation + optional ID upload
- `login.php`: user login, role loading, optional admin-mode login
- `adminlogin.php`: admin/manager login entry

### `pages/admin/`

- `auth.php`: RBAC gate for admin area (`admin` or `manager`)
- `index.php`: dashboard by role
- `users.php`, `users_edit.php`: manage users/user types, approve IDs, role assignment actions
- `roles.php`, `role_create.php`, `role_edit.php`, `role_delete.php`: role CRUD
- `logout.php`: ends admin session

## 3) Database Model and Relationships

Defined in `database.sql`.

### Core tables

- `users`
  - Stores account identity and verification state (`id_verified`, `id_document`)
- `listings`
  - Product listings linked to `users.id` by `user_id`
  - Includes seller type (`casual`/`informal`), status, and item fields
- `listing_images`
  - 1-to-many images per listing
- `messages`
  - chat messages tied to `listing_id`, `sender_id`, `receiver_id`
- `orders`
  - buyer/seller/product + amount + delivery + status + payment_status + tracking

### RBAC and user-type tables

- `roles`, `user_roles`: role-based access control
- `buyers`, `admin_accounts`, `manager_accounts`, `informal_sellers`: category tables used by admin tooling

## 4) Authentication and Authorization Logic

## User login

`pages/auth/login.php`:

- Finds user by email
- Verifies password hash with `password_verify`
- Loads roles from `roles` + `user_roles`
- Stores session keys: `user_id`, `user_name`, `verified`, `roles`, `is_admin`
- In admin mode, blocks non-admin/non-manager users from admin redirect

## Registration

`pages/auth/register.php`:

- Validates DB availability
- Uploads optional ID to `images/ids/`
- Hashes password via `password_hash`
- Inserts user with default `id_verified = 0`
- Tries to assign role `user` and insert into `buyers`

## Admin access

- `pages/admin/auth.php` allows only users with `admin` or `manager` role
- `pages/auth/adminlogin.php` supports:
  - DB-backed admin/manager credentials
  - fallback env-based credentials from `secret.env`

## 5) Listing Lifecycle Logic

### Create listing (`pages/sell.php`)

1. Requires signed-in user (`$_SESSION['user_id']`)
2. If seller type is `informal`, enforces `users.id_verified = 1`
3. Inserts listing record in `listings`
4. Handles image upload:
   - base64 image payload path (`image_data`) and/or file upload fallback
   - stores paths in `listing_images`
5. Marks listing as `active`

### Browse listings (`pages/alllistings.php` + category pages)

- Queries `listings` joined with `users` to include verification badge info
- Filters by active status and/or seller type depending on page
- Pulls first listing image from `listing_images`

### Manage own listings (`pages/mylistings.php`)

- Seller can update title/description/price/location/status for own record
- Seller can delete own listing by `id` + `user_id` ownership check

## 6) Purchase and Escrow Flow

### Step A: Item view (`pages/buy.php`)

- Requires login
- Loads listing + seller details
- Prevents buying your own listing
- Shows choice between courier and meetup in modal
- Buy button leads to checkout/payment path

### Step B: Checkout (`pages/checkout_new.php`)

- Creates an `orders` row with initial status `PENDING`
- Delivery logic:
  - `courier`: adds fixed fee (R 80), escrow required
  - `meetup`: no courier fee, escrow not required
  - `chat`: redirects to `messages.php` without creating payment flow
- Stores temporary escrow flag in session per order
- Redirects to payment simulation page

### Step C: Payment simulation (`pages/fake_payment_new.php`)

- Validates that order belongs to logged-in buyer
- Generates fake transaction ID
- For courier escrow orders:
  - sets `status = HELD_IN_ESCROW`
  - sets `payment_status = escrow_held`
  - stores transaction/time
  - notifies seller via `mail()`
- For meetup:
  - sets `status = PENDING_MEETUP`
  - sets `payment_status = paid`
- Redirects to tracking page

### Step D: Tracking and release (`pages/trackorder.php` + `pages/orders.php`)

`trackorder.php`:

- Loads active buyer orders (excluding completed/cancelled/refunded)
- Seeds tracking numbers for courier orders using `data/delivery.json`
- Maps simulated delivery states into order statuses (`SHIPPED`, `DELIVERED`)
- Buyer can mark item received, updating status and payment release state

`orders.php`:

- Buyer can confirm delivery for `DELIVERED` orders
- Moves order to `COMPLETED`
- Sets `payment_status = released_to_seller`
- Sends seller notification and logs release

## 7) Messaging Flow

`pages/messages.php`:

- Supports new conversation creation from listing context
- Messages are per `(user pair + listing_id)` thread
- Conversation list shows last message and unread count
- Opening chat marks incoming messages as read

## 8) Profile and Verification Logic

`pages/profile.php`:

- If not logged in: prompts to sign in/sign up
- If logged in:
  - loads user row
  - allows profile updates
  - allows ID file upload to support verification workflow
- `id_verified` controls trust badge behavior and informal seller eligibility

## 9) Admin and Role Management Logic

### Admin dashboard (`pages/admin/index.php`)

- Shows different navigation for `admin` vs `manager`

### Users management (`pages/admin/users.php`)

- Ensures type tables and default roles exist
- Syncs `informal_sellers` and `buyers` sets based on listing activity and roles
- Admin-only actions include:
  - approve user ID (`id_verified = 1`)
  - create admin/manager accounts by email
  - assign roles and maintain type-table consistency
  - demote admin/manager to buyer
  - delete users
- Managers can access user management UI but are restricted from admin-only mutations

### Roles CRUD (`pages/admin/roles.php` + role\_\* files)

- Admin can create/edit/delete role records in `roles`

## 10) Configuration and Environment

`pages/db.php` reads `secret.env` and sets PDO using:

- `DB_HOST`
- `DB_PORT`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`

Fallback defaults are localhost/root-style values if env keys are absent.

`secret.env` also supports fallback admin and manager credentials used in admin login page.

## 11) Notes About Current Codebase Behavior

These are important implementation details observed in the code:

- Many pages combine business logic and HTML in the same file (simple, but harder to maintain at scale).
- Session values include fields that may not exist on `users` (`seller_type` in login), which can produce notices depending on error settings.
- Several files appear to contain mixed/duplicated HTML-CSS fragments; this does not change the conceptual architecture but can affect rendering/maintainability.
- Email notifications use PHP `mail()` and may depend on local server mail configuration.

## 12) End-to-End User Journey (Summary)

1. User registers in `auth/register.php`
2. User logs in via `auth/login.php`
3. Seller lists item in `sell.php`
4. Buyer browses listings in `alllistings.php` / category pages
5. Buyer opens item in `buy.php` and proceeds to `checkout_new.php`
6. Payment simulation in `fake_payment_new.php` updates escrow/payment status
7. Buyer tracks order in `trackorder.php`
8. Buyer confirms delivery in `orders.php`/`trackorder.php`, releasing escrow
9. Buyer and seller communicate in `messages.php` throughout process

---

If you want, I can also generate a second file with sequence diagrams for these flows (auth, listing, escrow, and messaging) so onboarding new developers is faster.
