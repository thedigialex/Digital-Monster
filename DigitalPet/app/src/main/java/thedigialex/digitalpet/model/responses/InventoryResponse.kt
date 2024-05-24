package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.Inventory

data class InventoryResponse(
    val status: Boolean,
    val message: String,
    val inventories: List<Inventory>
)
