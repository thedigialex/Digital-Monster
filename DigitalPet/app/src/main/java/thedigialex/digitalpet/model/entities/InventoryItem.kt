package thedigialex.digitalpet.model.entities

data class InventoryItem(
    val id: Int,
    val userId: Int,
    val itemId: Int,
    val isEquipped: Int,
    var quantity: Int,
    val item: Item
)