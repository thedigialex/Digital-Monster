package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.Item

data class ItemResponse(
    val status: Boolean,
    val message: String,
    val items: List<Item>
)
