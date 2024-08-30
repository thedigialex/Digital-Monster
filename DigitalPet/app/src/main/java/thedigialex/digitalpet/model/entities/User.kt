package thedigialex.digitalpet.model.entities

data class User(
    val id: Long,
    val name: String,
    val email: String,
    val bits: Int,
    val userDigitalMonsters: List<UserDigitalMonster>? = null,
    var mainDigitalMonster: UserDigitalMonster? = null,
    var inventory: List<Inventory>? = null
)
