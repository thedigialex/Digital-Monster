package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.DigitalMonster

data class DigitalMonsterResponse(
    val status: Boolean,
    val message: String,
    val digitalMonster: DigitalMonster?
)
