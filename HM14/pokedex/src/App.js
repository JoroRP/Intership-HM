import React, {useState, useEffect} from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './index.css';

function App() {
    const [selectedPokemon, setSelectedPokemon] = useState(null);

    return (
        <div className="container">
            {selectedPokemon ? (
                <PokemonDetail name={selectedPokemon} goBack={() => setSelectedPokemon(null)}/>
            ) : (
                <Pokedex onSelectPokemon={(name) => setSelectedPokemon(name)}/>
            )}
        </div>
    );
}

const Pokedex = ({onSelectPokemon}) => {
    const [pokemonList, setPokemonList] = useState([]);
    const [pokemonData, setPokemonData] = useState({});
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        fetch('https://pokeapi.co/api/v2/pokemon?limit=500')
            .then((response) => response.json())
            .then((data) => {
                setPokemonList(data.results);
                data.results.forEach((pokemon) => {

                    fetch(pokemon.url)
                        .then((response) => response.json())
                        .then((pokeData) => {
                            setPokemonData((prevData) => ({
                                ...prevData,
                                [pokemon.name]: {
                                    id: pokeData.id,
                                    image: pokeData.sprites.front_default,
                                    types: pokeData.types.map((typeInfo) => typeInfo.type.name),
                                },
                            }));
                        });
                });
            });
    }, []);

    const handleSearch = (e) => {
        setSearchTerm(e.target.value.toLowerCase());
    };

    const filteredPokemon = pokemonList.filter((pokemon) =>
        pokemon.name.toLowerCase().includes(searchTerm)
    );

    return (
        <div className="pokedex-container">
            <h1 className="text-center mb-4">Pokedex</h1>
            <input
                type="text"
                className="form-control mb-4"
                placeholder="Search Pokémon by name..."
                onChange={handleSearch}
                value={searchTerm}
            />
            <div className="row">
                {filteredPokemon.map((pokemon) => (
                    <div className="col-lg-3 col-md-4 col-sm-6 mb-4" key={pokemon.name}>
                        <div className="pokemon-card card text-center">
                            <img
                                src={pokemonData[pokemon.name]?.image}
                                alt={pokemon.name}
                                className="pokemon-img card-img-top"
                            />
                            <div className="card-body">
                                <h5 className="pokemon-name card-title">{pokemon.name}</h5>
                                <p className="pokemon-number">#{pokemonData[pokemon.name]?.id}</p>
                                <div className="pokemon-type-container">
                                    {pokemonData[pokemon.name]?.types.map((type, idx) => (
                                        <span
                                            key={idx}
                                            className={`pokemon-type badge type-${type} mr-1`}
                                        >
                                            {type}
                                        </span>
                                    ))}
                                </div>
                                <button
                                    className=" mt-3 custom-button"
                                    onClick={() => onSelectPokemon(pokemon.name)}
                                >
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

const PokemonDetail = ({name, goBack}) => {
    const [pokemonData, setPokemonData] = useState(null);

    useEffect(() => {
        fetch(`https://pokeapi.co/api/v2/pokemon/${name}`)
            .then((response) => response.json())
            .then((data) => setPokemonData(data));
    }, [name]);

    if (!pokemonData) return <div>Loading...</div>;

    const getStatClassName = (statName) => {
        switch (statName.toLowerCase()) {
            case 'hp':
                return 'stat-hp';
            case 'attack':
                return 'stat-attack';
            case 'defense':
                return 'stat-defense';
            case 'special-attack':
                return 'stat-special-attack';
            case 'special-defense':
                return 'stat-special-defense';
            case 'speed':
                return 'stat-speed';
            default:
                return '';
        }
    };

    return (
        <div className="container">
            <button className="back-button mb-4" onClick={goBack}>
                Back to Pokedex
            </button>
            <div className="row mt-4 align-items-center">
                <div className="col-md-4">
                    <img
                        src={pokemonData.sprites.front_default}
                        alt={pokemonData.name}
                        className="pokemon-img w-100"
                    />
                </div>
                <div className="col-md-8">
                    <div className="pokemon-header">
                        <span>{pokemonData.name}</span>
                        <span className="detail-number">#{pokemonData.id}</span>
                    </div>
                    <h3>Type</h3>
                    {pokemonData.types.map((typeInfo, index) => (
                        <span
                            key={index}
                            className={`pokemon-type badge type-${typeInfo.type.name} mr-2`}
                        >
                            {typeInfo.type.name}
                        </span>
                    ))}
                    <div className="card mt-4 info-card">
                        <div className="card-body">
                            <h4>Abilities, Height, and Weight</h4>
                            <p>
                                <strong>Abilities:</strong>{' '}
                                {pokemonData.abilities.map((abilityInfo) => abilityInfo.ability.name).join(', ')}
                            </p>
                            <p>
                                <strong>Height:</strong> {pokemonData.height / 10} m
                            </p>
                            <p>
                                <strong>Weight:</strong> {pokemonData.weight / 10} kg
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <h3 className="mt-4">Stats</h3>
            <div className="row">
                {pokemonData.stats.map((stat, index) => (
                    <div className={`col-md-4 mb-3 ${getStatClassName(stat.stat.name)}`} key={index}>
                        <div className="card text-center p-3">
                            <h4>{stat.stat.name}</h4>
                            <p>{stat.base_stat}</p>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default App;
