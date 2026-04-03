# Pornire automată ActeRomânia (systemd)

Serviciul systemd pornește aplicația la boot, o repornește la crash și după restart server.

## Instalare (o singură dată)

```bash
# Copiază unitatea în systemd
sudo cp /home/john/acteromania/deploy/acteromania.service /etc/systemd/system/

# Reîncarcă systemd
sudo systemctl daemon-reload

# Activează pornirea la boot
sudo systemctl enable acteromania

# Pornește serviciul acum
sudo systemctl start acteromania
```

## Comenzi utile

| Comandă | Descriere |
|--------|-----------|
| `sudo systemctl status acteromania` | Status și dacă rulează |
| `sudo systemctl start acteromania` | Pornește |
| `sudo systemctl stop acteromania` | Oprește |
| `sudo systemctl restart acteromania` | Repornește |
| `journalctl -u acteromania -f` | Loguri în timp real |

## Comportament

- **La pornirea PC/serverului:** serviciul pornește automat (după rețea și MySQL).
- **Dacă procesul PHP se oprește sau crapă:** systemd îl repornește după 3 secunde (`Restart=always`, `RestartSec=3`).
- **Port:** 8080 (acces: `http://<IP>:8080` și `http://<IP>:8080/admin`).

## Firewall

Dacă folosești UFW, portul 8080 trebuie permis (deja făcut anterior):

```bash
sudo ufw allow 8080/tcp
sudo ufw status
```
