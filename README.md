# Joaquin Estrada — Portfolio

Portfolio minimalista dark & cinematic. Auto-scan de imágenes con PHP.

---

## Estructura de archivos

```
/
├── index.php              ← página principal (no tocar)
├── css/style.css          ← estilos (podés personalizar)
├── js/main.js             ← interacciones (no tocar)
├── .htaccess              ← configuración del servidor
└── works/
    ├── concept-art/       ← ← ← subí tus imágenes acá
    └── 3d/                ← ← ← subí tus imágenes acá
```

---

## Cómo subir imágenes

1. Entrá a **cPanel → Administrador de archivos**
2. Navegá hasta `public_html/works/concept-art/` o `public_html/works/3d/`
3. Subí tus imágenes (arrastrá y soltá o botón "Subir")
4. ¡Listo! Aparecen automáticamente en el portfolio

Alternativamente podés usar **FileZilla** o cualquier cliente FTP.

### Nombre de archivos → Título en el portfolio

| Archivo | Título mostrado |
|---|---|
| `character-dark-knight.jpg` | Character Dark Knight |
| `env_forest_concept.png` | Env Forest Concept |
| `01-creature-zbrush.jpg` | Creature Zbrush *(números iniciales se eliminan)* |

Las imágenes **más nuevas aparecen primero**.

---

## Personalización rápida

Abrí `index.php` con cualquier editor de texto:

### Cambiar bio (sección About)
Buscá `<!-- ABOUT -->` y editá los párrafos `<p>...</p>`.

### Cambiar email de contacto
Buscá `mailto:tu@email.com` y reemplazalo.

### Agregar links de redes sociales
Buscá los tres `<a href="#">` en la sección Contact y pegá tus URLs.

### Agregar una categoría nueva
1. En `index.php`, al inicio del PHP, agregá:
   ```php
   $works_nueva = scan_gallery('works/nueva-cat', 'nueva-cat', 'Mi Categoría', $IMG_EXTS);
   $all_works   = array_merge($works_ca, $works_3d, $works_nueva);
   ```
2. Creá la carpeta `works/nueva-cat/`
3. Agregá un botón de filtro en el HTML:
   ```html
   <button class="filter-btn" data-filter="nueva-cat">Mi Categoría</button>
   ```

---

## Requisitos del servidor

- PHP **7.0 o superior** (cPanel casi siempre tiene 7.4+)
- Apache con `mod_rewrite` y `mod_expires` (estándar en cPanel)
- No requiere base de datos

---

## Tamaño recomendado de imágenes

| | Recomendado | Máximo |
|---|---|---|
| Ancho | 1500 px | 2500 px |
| Peso | 800 KB | 2 MB |
| Formato | WebP | cualquiera |

Convertí gratis en: **squoosh.app**

---

## Subir al servidor (primer deploy)

1. En cPanel → Administrador de archivos → `public_html/`
2. Subí **todos** los archivos y carpetas
3. Asegurate de que `index.php` quede en la raíz de `public_html/`
4. Visitá `joaquinestrada.com.ar` — ¡debería funcionar!

Si usás FTP (FileZilla):
- Host: `ftp.joaquinestrada.com.ar` (o el que te dio beewh)
- Usuario/contraseña: los de tu cuenta cPanel
- Directorio remoto: `/public_html/`
