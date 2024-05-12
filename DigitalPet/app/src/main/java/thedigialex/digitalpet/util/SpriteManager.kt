package thedigialex.digitalpet.util

import android.content.res.Resources
import android.graphics.Bitmap
import android.graphics.BitmapFactory

object SpriteManager {
    fun splitSpriteSheet(resources: Resources, resourceId: Int, tilesPerRow: Int): List<Bitmap> {
        val spriteSheet = BitmapFactory.decodeResource(resources, resourceId)
        val tileWidth = spriteSheet.width / tilesPerRow
        val tiles = mutableListOf<Bitmap>()
        for (x in 0 until tilesPerRow) {
            val tile = Bitmap.createBitmap(
                spriteSheet,
                x * tileWidth,
                0,
                tileWidth,
                spriteSheet.height
            )
            tiles.add(tile)
        }
        return tiles
    }
}
