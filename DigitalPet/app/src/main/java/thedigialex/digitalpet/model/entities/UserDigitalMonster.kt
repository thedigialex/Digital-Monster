package thedigialex.digitalpet.model.entities

data class UserDigitalMonster(
    val id: Int,
    val userId: Long,
    val isMain: Int,
    var name: String,
    val type: String,
    val attack: Int,
    val colosseum: Int,
    val level: Int,
    val exp: Int,
    var strength: Int,
    var agility: Int,
    var defense: Int,
    var mind: Int,
    var hunger: Int,
    var exercise: Int,
    var clean: Int,
    var energy: Int,
    val maxEnergy: Int,
    val wins: Int,
    val losses: Int,
    var trainings: Int,
    var maxTrainings: Int,
    var currentEvoPoints: Int,
    var sleepStartedAt: String?,
    val digital_monster: DigitalMonster,
)