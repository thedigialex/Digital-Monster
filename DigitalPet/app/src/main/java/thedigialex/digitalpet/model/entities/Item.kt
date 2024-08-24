package thedigialex.digitalpet.model.entities

data class Item(
    val id: Int,
    val name: String,
    val image: String,
    val type: String,
    val price: Int,
    val available: Int,
    val rarity: String,
)
