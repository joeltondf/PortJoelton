<?php
if (!defined('BASE_URL')) exit;
/**
 * Helpers de Otimização de Imagens — WebP Converter
 * 
 * Inclua este arquivo onde for necessário:
 *   require_once __DIR__ . '/../api/image_helper.php';
 * 
 * Uso:
 *   $url = getOptimizedImageUrl('images/MCTI/MCTI-Livro-1.png');
 */

// Root físico das imagens (caminho absoluto)
if (!defined('IMAGES_DOC_ROOT')) {
    define('IMAGES_DOC_ROOT', dirname(__DIR__)); // public/
}

/**
 * Converte PNG/JPG para WebP usando GD (se disponível)
 * 
 * @param string $sourcePath  Caminho absoluto da imagem original
 * @param string $destPath    Caminho absoluto de destino .webp
 * @param int    $quality     Qualidade WebP (0-100)
 * @return bool               True se convertido com sucesso
 */
function convertToWebP(string $sourcePath, string $destPath, int $quality = 82): bool {
    if (!function_exists('imagewebp')) {
        return false; // GD sem suporte WebP
    }
    
    if (!file_exists($sourcePath)) {
        return false;
    }
    
    // Garantir que o diretório de destino existe
    $destDir = dirname($destPath);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    
    $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
    
    try {
        $image = null;
        
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $image = @imagecreatefrompng($sourcePath);
                if ($image) {
                    // Preservar transparência PNG
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'gif':
                $image = @imagecreatefromgif($sourcePath);
                break;
        }
        
        if (!$image) {
            return false;
        }
        
        $result = imagewebp($image, $destPath, $quality);
        imagedestroy($image);
        
        return $result;
        
    } catch (Throwable $e) {
        error_log("WebP conversion failed for $sourcePath: " . $e->getMessage());
        return false;
    }
}

/**
 * Retorna URL otimizada da imagem (WebP se disponível, fallback para original)
 * 
 * @param string $relativePath  Caminho relativo da imagem (ex: 'images/MCTI/MCTI-Livro-1.png')
 * @param bool   $autoConvert   Se deve tentar converter automaticamente
 * @return string               Caminho relativo para usar em src=""
 */
function getOptimizedImageUrl(string $relativePath, bool $autoConvert = true): string {
    if (empty($relativePath)) {
        return $relativePath;
    }
    
    // Ignora se já for URL externa
    if (str_starts_with($relativePath, 'http')) {
        return $relativePath;
    }
    
    $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
    
    // Se o formato não for suportado para conversão
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $sourceAbsolute = IMAGES_DOC_ROOT . '/' . $relativePath;
        if (file_exists($sourceAbsolute)) {
            return BASE_URL . '/' . $relativePath;
        } else {
            return 'https://joelton.com.br/' . $relativePath;
        }
    }
    
    // Se já for webp, verifica se existe localmente
    if ($ext === 'webp') {
        $sourceAbsolute = IMAGES_DOC_ROOT . '/' . $relativePath;
        if (file_exists($sourceAbsolute)) {
            return BASE_URL . '/' . $relativePath;
        } else {
            return 'https://joelton.com.br/' . $relativePath;
        }
    }
    
    // Caminho WebP correspondente
    $webpRelative = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $relativePath);
    $webpAbsolute = IMAGES_DOC_ROOT . '/' . $webpRelative;
    $sourceAbsolute = IMAGES_DOC_ROOT . '/' . $relativePath;
    
    // Verificar se WebP já existe localmente
    if (file_exists($webpAbsolute)) {
        return BASE_URL . '/' . $webpRelative;
    }
    
    // Tentar converter na primeira carga
    if ($autoConvert && file_exists($sourceAbsolute)) {
        $converted = convertToWebP($sourceAbsolute, $webpAbsolute);
        if ($converted) {
            return BASE_URL . '/' . $webpRelative;
        }
    }
    
    // Se o arquivo original existe localmente, use-o
    if (file_exists($sourceAbsolute)) {
        return BASE_URL . '/' . $relativePath;
    }
    
    // Fallback final: se não existe localmente, busca o WebP da produção
    return 'https://joelton.com.br/' . $webpRelative;
}

/**
 * Gera tag <img> completa com lazy loading, WebP, alt e dimensões
 * 
 * @param string $src        Caminho relativo da imagem
 * @param string $alt        Texto alternativo
 * @param string $class      Classes CSS
 * @param bool   $eager      Se true, usa loading="eager" (para above-the-fold)
 * @return string            HTML da tag img
 */
function optimizedImg(string $src, string $alt = '', string $class = '', bool $eager = false): string {
    $optimizedSrc = getOptimizedImageUrl($src);
    $loading = $eager ? 'eager' : 'lazy';
    $altEscaped = htmlspecialchars($alt);
    $classAttr = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    
    return "<img src=\"{$optimizedSrc}\"{$classAttr} alt=\"{$altEscaped}\" loading=\"{$loading}\" decoding=\"async\">";
}
