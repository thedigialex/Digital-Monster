package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.InventoryItem

data class InventoryItemResponse(
    val status: Boolean,
    val message: String,
    val inventoryItems: List <InventoryItem>
)
