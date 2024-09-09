package thedigialex.digitalpet.model.entities

data class UserDigitalMonster(
    val id: Int,
    val userId: Long,
    val digitalMonsterId: Int,
    val isMain: Int,
    var name: String,
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
    val energy: Int,
    val maxEnergy: Int,
    val wins: Int,
    val losses: Int,
    val trainings: Int,
    val maxTrainings: Int,
    val evoPoints: Int,
    var digital_monster: DigitalMonster
)
