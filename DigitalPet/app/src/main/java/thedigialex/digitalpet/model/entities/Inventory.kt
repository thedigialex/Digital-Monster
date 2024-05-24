package thedigialex.digitalpet.model.entities

data class Inventory(
    val id: Int,
    val userId: Int,
    val itemId: Int,
    val quantity: Int,
    val item: Item
)
