package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.UserTrainingEquipment

data class UserTrainingEquipmentResponse(
    val status: Boolean,
    val message: String,
    val userTrainingEquipment: List<UserTrainingEquipment>
)