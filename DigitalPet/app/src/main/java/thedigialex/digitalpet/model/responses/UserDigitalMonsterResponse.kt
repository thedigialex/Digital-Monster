package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.UserDigitalMonster

data class UserDigitalMonsterResponse (
    val status: Boolean,
    val message: String,
    val userDigitalMonsters: List<UserDigitalMonster>
)