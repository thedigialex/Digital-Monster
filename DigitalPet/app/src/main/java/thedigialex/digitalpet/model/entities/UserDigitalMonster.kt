package thedigialex.digitalpet.model.entities

data class UserDigitalMonster(
    val id: Int,
    val userId: Int,
    val digitalMonsterId: Int,
    val isMain: Int,
    val name: String,
    val type: String,
    val age: Int,
    val level: Int,
    val exp: Int,
    val strength: Int,
    val agility: Int,
    val defense: Int,
    val mind: Int,
    val hunger: Int,
    val exercise: Int,
    val clean: Int,
    val weight: Int,
    val energy: Int,
    val wins: Int,
    val losses: Int,
    val trainings: Int,
    val careMisses: Int,
    val createdAt: String,
    val updatedAt: String,
    val digital_monster: DigitalMonster
)
