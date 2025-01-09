package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.DigitalMonster

data class DigitalMonstersResponse(
    val status: Boolean,
    val message: String,
    val digitalMonsters: List<DigitalMonster>
)
