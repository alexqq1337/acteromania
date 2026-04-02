# ActeRomânia - Setup pe Server

## 📋 Pași după clonarea repository-ului:

### 1. Clonare repository
```bash
cd /var/www/html  # sau calea ta
git clone https://github.com/username/acteromania.git
cd acteromania
```

### 2. Configurare bază de date
```bash
# Creează database
mysql -u root -p -e "CREATE DATABASE acteromania_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importă structura
mysql -u root -p acteromania_cms < database.sql
```

### 3. Actualizează config.php
```php
// Modifică aceste linii:
define('DB_HOST', 'localhost');
define('DB_NAME', 'acteromania_cms');
define('DB_USER', 'root');          // user-ul tău MySQL
define('DB_PASS', 'parola_ta');      // parola MySQL

define('SITE_URL', 'https://domeniultau.ro');  // URL-ul site-ului
```

### 4. Configurare Admin Panel (.htaccess)
```bash
cd control-panel-cetateniero

# Copiază template-ul
cp .htaccess.example .htaccess

# Creează fișierul de parole
htpasswd -c .htpasswd admin
# Introdu parola: ActeRO2026!Secure (sau alta)

# Editează .htaccess și actualizează calea absolută:
nano .htaccess
# Schimbă linia AuthUserFile cu calea completă către .htpasswd
# Exemplu: AuthUserFile /var/www/html/acteromania/control-panel-cetateniero/.htpasswd
```

### 5. Permisiuni
```bash
cd /var/www/html/acteromania
chmod 755 .
chmod -R 755 control-panel-cetateniero/
chmod 644 control-panel-cetateniero/.htpasswd
chmod 644 control-panel-cetateniero/.htaccess
chmod 755 logs/
chmod 755 reviews/
```

### 6. Credențiale Admin

**URL Admin Panel:**
- `https://domeniultau.ro/control-panel-cetateniero/`

**Autentificare Apache (.htaccess):**
- User: `admin`
- Pass: (parola setată cu htpasswd)

**Autentificare CMS:**
- User: `admin`
- Pass: `cetateniero19736482`

### 7. Verificare funcționare
```bash
# Testează site-ul
curl -I https://domeniultau.ro

# Testează admin panel (ar trebui să returneze 401)
curl -I https://domeniultau.ro/control-panel-cetateniero/
```

---

## 🔐 Securitate Important:

1. **NU uita să actualizezi** calea în `.htaccess`
2. **Schimbă parolele** pentru producție dacă e necesar
3. **Verifică permisiunile** fișierelor sensibile
4. **Configurează HTTPS** (Let's Encrypt recomandat)
5. **Backup regulat** la baza de date

## 📱 Telegram Bot
- Bot-ul e deja configurat în `send_contact.php`
- Token: 8748722673:AAHoGUMqzPfjyrYVn2AMWQlSS2gMH80Czxo
- Chat ID: -1003734447374
